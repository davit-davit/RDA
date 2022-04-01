<?php
/**
 * @author Davit Tchetchelashvili
 * @package rda
 * */
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Hash;
use App\Models\Users; // მომხმარებლების ცხრილის მოდელი
use App\Models\ResetPass; // პაროლის აღდგენისათვის შემთხვევითი კოდისა და იმეილის ცხრილის მოდელი
use App\Models\Test; // ტესტების ცხრილის მოდელი
use App\Models\Question; // კითხვების ცხრილის მოდელი
use App\Models\TestsQuestions; // იმ კითხვების ცხრილის მოდელი, რომლებიც უშუალოდ კონკრეტულ ტესტს ეკუთვნის
use App\Models\Subjects; // თემების ცხრილის მოდელი, რომლებიც კითხვებს ეკუთვნით
use App\Models\Taken; // კონკრეტული იუზერზე მიკუტვნებული ტესტების ცხრილის მოდელი
use App\Models\Notifications; // შეტყობინებებისცხრილის მოდელი
use App\Models\Answer; // პასუხების ცხრილის მოდელი
use App\Models\SubmitedTests; // დადასტურებული ტესტების ცხრილის მოდელი
use App\Models\History; // კითხვების რედაქტირების ისტორიების ცხრილის მოდელი
use Auth; // ფასადი აუტენტიფიკაციისთვის
use Mail; // ფასადი, რომელიც უზრუნველყოფს იმეილის გაგზავნა
use DB; // მონაცემთა ბაზასთან და მის ბრძანებებთან სამუშაო ფასადი
use Barryvdh\DomPDF\Facade as PDF; // pdf დასაგენირებელი ბიბლიოთეკა (ხელსაწყო)

class MainController extends Controller
{
    /**
     * ავტორიზაციის გვერდის დამრენდერებელი მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-2 ხაზზე
     * @method GET
     */
    public function Login() {
        return view("login");
    }

    /**
     * ავტორიზაციის გავლისას ეს მეთოდი მოახდენს მომხმარებლის მიერ შეტანილი მონაცემების
     * ვალიდაციას და სესაბამისი შეტყობინებების დაგენერირებას
     * @param Illuminate\Http\Request $request
     * @method POST
     * */
    // მეთოდის მარშუტი არის routes/web.php ფაილში მე-3 ხაზზე
    public function Login_Valid(Request $request) {
        if($request->has("login") && $request->method() == "POST") {
            $credentials = $this->validate($request, [
                "email" => "required|email|min:7|max:50",
                "password" => "required|min:4|max:100"
            ]);
            // მოხდება მომხმარებლის აუთენტიფიკაცია მთავარ გვერდზე
            if(Auth::attempt($credentials)) {
                $request->session()->regenerate();
                $user = Auth::user();
                $user->active_status = "active";
                $user->save();
                return redirect("/home");
            }else {
                return back()->with("error", "იმეილი ან პაროლი არასწორია!");
            }
        }
    }

    /**
     * სისტემიდან გამოსვლის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-5 ხაზზე
     * @param Illuminate\Http\Request $request
     * @return ბრუნდება მთავარ(ავტორიზაციის) გვერდზე
     * @method POST
     * */
    public function Logout(Request $request) {
        if($request->route()->named("logout")) {
            if(auth()->check()) {
                $logged = Auth::user();
                $logged->active_status = "inactive"; // სისტემიდან გამოსვლისას იუზერის ცხრილში
                // განისაზღვრება(მიენიჭება კონკრეტულ სვეტს კონკრეტული იუზერისთვის) მომხმარებლის აქტიურობა/არააქტიურობა
                $logged->save();
                auth()->logout();
                $request->session()->flush(); // ხდება სესიის გაუქმება, წაშლა, გასუფთავება
            }
            return redirect("/");
        }
    }

    /**
     * მომხმარებლის/Dashboard-ის მთავარი გვერდის მეთოდი
     * პარამეტრი @param $t გათვალისწინებულია იმისათვის, რომ მოხდეს გადამოწმება, მომხმარებელმა შეასრულა თუ არა ტესტირება
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-11 ხაზზე
     * @method GET
     */
    public function Home($t = null) {
        if(Auth::check()) {
            $logged_in = Auth::user();
            $logged_in->active_status = "active";
            $logged_in->save();

            $user = Users::where("id", Auth::user()->id)->get();

            return view("index")->with([
                "data" => $user, // ცვლადი, რომელშიც ინახება მომხმარებლის პირადი ინფორმაციები
                "t" => $t // ცვლადი იღებს მნიშვნელობად url-ში გადაცემულ პარამეტრს, რომლითაც ხდება შემოწმება, რომ ტესტი ნამდვილად დაასრულა მომხმარებელმა,
            ]);
        }else {
            return redirect("/");
        }
    }

    /**
     * ეს მეთოდი არენდერებს პაროლის აღდგენის გვერდს
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-7 ხაზზე
     * @method GET
     * */
    public function ResetPass() {
        return view("password_reset");
    }

    /**
     * პაროლის აღსადგენი მეთოდი, მისთვისვე განკუთვნილი მარშუტისათვის
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-9 ხაზზე
     * @param Illuminate\Http\Request $request
     * @return redirection back | საწყის გვერდზე გადამისამართება
     * @method POST
     * */
    public function Reset_Validate(Request $request) {
        $this->validate($request, [
            "email" => "required|email|min:7|max:50",
        ]);

        //მასივი, რომლის ელემენტებისგანაც უნდა მოხდეს შემთხვევითი
        //სტრიქონის შედგენა
        $letters = ["a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "A", "B", "C", "D", "E", "F", "G", "H", "I", "J", "K", "L", "M", "N", "O", "P", "Q", "R", "S", "T", "U", "V", "W", "X", "Y", "Z", 1, 2, 3, 4, 5, 6, 7, 8, 9, "$", "!", "@", "#", "%", "&", "(", ")"];

        $random_str = ""; // ამ ცვლადში შეინახება სიმბოლოები მიყოლებით, რითაც შეიქმნება უსაფრთხოების
        //კოდი
        /**
         * მინიმალური და მაქსიმალური მნიშვნელობები შემთხვევითი
         * სიმბოლოს დასაგენერირებლად
         * @var მინიმალური,მაქსიმალური*/
        $rand_min = 0;
        $rand_max = count($letters) - 1;

        for($i = 0; $i < 6; $i++) {
            $random_str .= $letters[random_int($rand_min, $rand_max)];
        }
        // დაგენერირებული შემთხვევითი სტრიქონი სეინახება მონაცემთა ბაზის ცხრილში
        // რათა პაროლის აღდგენის შემდგომ იმეილზე მისული სტრიქონი გადამოწმდეს მართლა არსებობს
        // თუ არა ბაზაში
        $create = ResetPass::create([
            "random_string" => $random_str,
            "email" => $request->input("email")
        ]);

        // მოხდება მაილის გაგზავნის მცდელობა და შეცდომის/გამონაკლისის არსებობის
        // შემთხვევაში მოხდება საწყის გვერდზე გადამისამართება და შეცდომის შეტყობინების
        // დაგენერირება
        try {
            Mail::send("layouts.mail", ["code" => $random_str], function($message) use($request, $random_str) {
                $message->to($request->input("email"));
                $message->from("davit.chechelashvili@geolab.edu.ge", "RDA პაროლის აღდგენა");
                $message->subject("კოდი");
            });
            return redirect("/reset")->with("status", "კოდი წარმატებით გაიგზავნა. გთხოვთ შეამოწმოთ იმეილი");
        }catch(Exception $e) {
            return redirect()->back()->with("error", "დაფიქსირდა შეცდომა!");
        }
    }

    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 33-ე ხაზზე
     * @return პაროლის აღდგენის გვერდი, სადაც იწერება იმეილზე მისული კოდი და ახალი პაროლი
     * @method GET
     * */
    public function PReset() {
        return view("reset");
    }

    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 36-ე ხაზზე
     * @param Illuminate\Http\Request $request
     * @method POST
     * */
    public function P_Reset(Request $request) {
        //გაგზავნილი მონაცემების ვალიდაცია
        $this->validate($request, [
            "random_code" => "required|min:6|max:6",
            "password" => "required"
        ]);

        $get_email = ResetPass::where("random_string", $request->input("random_code"))->first();
        $final_email = $get_email->email;

        try {
            $user = Users::where("email", $final_email)->first();
            $user->password = Hash::make($request->input("password"));
            $user->save();
            return redirect("/")->with("reset_success", "პაროლის აღდგენა წარმატებით დასრულდა. შეგიძლიათ შეხვიდეთ სისტემაში");
        }catch(Exception $e) {
            return redirect("/")->with("reset_error", "პაროლის აღდგენა ვერ მოხერხდა");
        }
    }

    /**
     * ეს მეთოდი პროფილის რედაქტირების გვერდს არენდერებს
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-13 ხაზზე
     * @method GET
     */
    public function EditProfile() {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        return view("edit_pages.edit");
    }

    /**
     * ეს მეთოდი არის განკუთვნილი პაროლის ცვლილების მარშუტისათვის
     * სადაც შესაბამისი ოპერაციები ხორციელდება
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-15 ხაზზე
     * @param Illuminate\Http\Request $request
     * @method POST
     * */
    public function Password_Change(Request $request) {
        $this->validate($request, [
            "oldpass" => "required|min:4",
            "newpass1" => "required|min:4",
            "newpass2" => "required|min:4|same:newpass1"
        ]);

        $user = Auth::user();

        if(Hash::check($request->oldpass, $user->password)) {
            $user->password = Hash::make($request->newpass1);
            $user->save();
            return response()->json(["status" => 1]);
        }else {
            return response()->json(["status" => 0]);
        }
    }

    /**
     * ეს მეთოდი არის განკუთვნილი ტელეფონის ნომრის ცვლილების მარშუტისათვის
     * სადაც შესაბამისი ოპერაციები ხორციელდება
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-17 ხაზზე
     * @param Illuminate\Http\Request $request
     * @method POST
     * */
    public function Phone_Change(Request $request) {
        try {
            $user = Auth::user();
            $user->phone = $request->input("phone");
            $user->save();
            return 1;
        }catch(Exception $e) {
            return 0;
        }
    }

    /**
     * ფოტოს ცვლილების მეთოდი, სადაც გადმოიგზავნება ატვირთული ფოტოს მონაცემები
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-19 ხაზზე
     * @param Illuminate\Http\Request $request
     * @method POST
     * */
    public function Change_Photo(Request $request) {
        $file = $request->file('image');
        $name = $file->getClientOriginalName();
        $file->move("profiles_photos", $name); // ხდება ფოტოს ფაილში შენახვა

        try {
            $user = Auth::user();
            $user->avatar = "profiles_photos/" . $name;
            $user->save();
            // შეტყობინების განახლება მოხდება და შეტყობინების ავტორის ავატარი განახლდება ცხრილში
            Notifications::where("author_name", Auth::user()->name)->update([
                "author_avatar" => Auth::user()->avatar
            ]);

            return redirect()->back(); // უკან გადმომისამართება
        }catch(Exception $e) {
            return "ფაილი ვერ აიტვირთა"; // დაწერს შეტყობინებას, რომ ფოტო ვერ აიტვირთა
        }
    }

    /**
     * მეთოდი დააბრუნებს თანამშრომლების გვერდს, რომელზეც ყველა თანამშრომლის ინფორმაცია იქნება გამოტანილი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 21-ე ხაზზე
     * @method GET
     */
    public function Employees() {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        return view("employees");
    }

    /**
     * თანამშრომლის ამოშლის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 23-ე ხაზზე
     * @param $id | მომხმარებლის აიდი რომლის წაშლაც უნდა მოხდეს
     * @method GET
     * */
    public function DeleteEmp($id) {
        try {
            Users::find($id)->delete();
            return redirect()->back();
        }catch(Exception $e) {
            return "ვერ წაიშალა";
        }
    }

    /**
     * თანამშრომლის დამატების მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 25-ე ხაზზე
     * @method GET
     */
    public function AddEmp() {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        return view("add");
    }

    /**
     * თანამშრომლის დამატების ვალიდაცია
     * მეთოდის მარშუტი არის routes/web.php ფაილში 27-ე ხაზზე
     * @method POST
     */
    public function Emp_Valid(Request $request) {
        if($request->has("add") && $request->method() == "POST") {
            try {
                // მოცემული კოდის საშუალებით ხდება შეყვანილი მონაცემების ვალიდაცია
                $this->validate($request, [
                    "firstname" => "required", // სახელის ველი არის სავალდებულო
                    "lastname" => "required", // გვარის ველი არის სავალდებულო
                    "email" => "required", // იმეილის ველი არის სავალდებულო
                    "pid" => "required", // პირადი ნომრის ველი არის სავალდებულო
                    "phone" => "required", //ტელეფონის  სახელის ველი არის სავალდებულო
                    "role" => "required", // როლის ველი არის სავალდებულო
                    "password" => "required|min:4" // პაროლის ველი არის სავალდებულო და მინიმუმ უნდა
                    // შედგებოდეს 4 სიმბოლოსგან
                ]);
                // გაგზავნილი მონაცემების ბაზაში შეყვანა
                Users::create([
                    "name" => $request->input("firstname"),
                    "lastname" => $request->input("lastname"),
                    "pid" => $request->input("pid"),
                    "phone" => $request->input("phone"),
                    "email" => $request->input("email"),
                    "department" => $request->input("department"),
                    "service" => $request->input("service"),
                    "position" => $request->input("position"),
                    "role" => $request->input("role"),
                    "password" => Hash::make($request->input("password"))
                ]);

                return redirect()->back()->with("success_emp", "თანამშრომელი დაემატა!");
            }catch(Exception $e) {
                return redirect()->back()->with("error_emp", "თანამშრომელი ვერ დაემატა!");
            }
        }
    }

    /**
     * თანამშრომლის დარედაქტირების მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 29-ე ხაზზე
     * @method GET
     */
    public function Edit($id) {
        $data = Users::find($id); // მონაცემთა ბაზიდან წამოიღებს მომხმარებლის მონაცემებს
        // რომლის აიდიც უდრის გადაცემულ პარამეტრს
        return $data;
    }

    /**
     * თანამშრომლის რედაქტირება POST მოთხოვნის გაგზავნის დროს
     * მეთოდის მარშუტი არის routes/web.php ფაილში 31-ე ხაზზე
     * @method POST
     */
    public function EdEmp($id, Request $request) {
        try {
            // რედაქტირებისას შეყვანილი მონაცემების გაფილტვრა/ვალიდაცია
            $this->validate($request, [
                "firstname" => "required", // სახელის ველი არის სავალდებულო
                "lastname" => "required", // გვარის ველი არის სავალდებულო
                "email" => "required", // იმეილის ველი არის სავალდებულო
                "pid" => "required", // პირადი ნომრის ველი არის სავალდებულო
                "phone" => "required", // ტელეფონის ველი არის სავალდებულო
                "role" => "required" // როლის ველი არის სავალდებულო
            ]);
            // გაგზავნილი მონაცემების განახლება ბაზაში
            $user_to_update = Users::find($id);

            $user_to_update->name = $request->input("firstname"); // სახელი
            $user_to_update->lastname = $request->input("lastname"); // გვარი
            $user_to_update->email = $request->input("email"); // იმეილი
            $user_to_update->pid = $request->input("pid"); // პირადი ნომერი
            $user_to_update->phone = $request->input("phone"); // ტელეფონი
            $user_to_update->department = $request->input("department"); // დეპარტამენტი
            $user_to_update->service = $request->input("service"); // სამსახური
            $user_to_update->position = $request->input("position"); // პოზიცია
            $user_to_update->role = $request->input("role"); // როლი

            $user_to_update->save(); // მიღებული მონაცემები შეინახება მონაცემთა ბაზაში/მოხდება არსებული
            // მონაცემების განახლება
            return response()->json(["edited" => 1]); // თუ მონაცემები დარედაქტირდა პროგრამა დააბრუნებს json მონაცემს { "edited" : 1 }. 1 ნიშნავს, რომ მონაცემები დარედაქტირდა
        }catch(Exception $e) {
            return response()->json(["edited" => 0]); // თუ მონაცემები ვერ დარედაქტირდა პროგრამა დააბრუნებს json მონაცემს { "edited" : 0 }. 0 ნიშნავს, რომ მონაცემები ვერ დარედაქტირდა
        }
    }

    /**
     * ტესტის შედგენის გვერდის რენდერი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 38-ე ხაზზე
     * @method GET
     */
    public function Create_Test() {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        return view("create_test");
    }

    /**
     * ტესტების გამოჩენის ფუნქცია AJAX მეთოდით
     * მეთოდის მარშუტი არის routes/web.php ფაილში 42-ე ხაზზე
     * @method GET
     */
    public function ShowTests() {
        $tests = Test::orderBy("id", "DESC")->paginate(30);
        return view("layouts.ajax_layouts.tests", compact("tests"))->render();
    }

    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-40 ხაზზე
     * @param Illuminate\Http\Request $request
     * @return redirection back | საწყის გვერდზე გადამისამართება
     * @method POST
     * */
    public function CreateTest(Request $request) {
        $this->validate($request, [
            "subject" => "required",
            "test_date" => "required",
            "test_start_time" => "required",
            "test_type" => "required",
            "wrong_score" => "required"
        ]);

        try {
            Test::create([
                "test_subject" => $request->input("subject"),
                "test_date" => $request->input("test_date"),
                "test_start_time" => $request->input("test_start_time"),
                "wrong_score" => $request->input("wrong_score"),
                "type" => $request->input("test_type")
            ]);
            return response()->json(["status_test" => 1]); // შეტყობინების დაგენერირება json ფორმატში
        }catch(Exception $e) {
            return response()->json(["status_test" => 0]); // შეტყობინების დაგენერირება json ფორმატში
        }
    }

    /**
     * ტესტის წაშლის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 44-ე ხაზზე
     * @param Illuminate\Http\Request $request
     * @method GET
     * */
    public function Delete_Test($id) {
        Test::where("id", $id)->delete();
        return redirect()->back();
    }

    /**
     * კითხვის დამატების მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 46-ე ხაზზე
     * @param Illuminate\Http\Request $request
     * @method GET
     * */
    public function AddQuestion() {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        $subjects = Subjects::all();
        return view("add_question")->with("subjects", $subjects);
    }

    /**
     * თემატიკების ავტომატურად განახლების ფუნქციონალი გვერდის სარენდეროდ
     * მეთოდის მარშუტი არის routes/web.php ფაილში 66-ე ხაზზე
     * @method GET
     */
    public function LoadSubjects() {
        $subjects = Subjects::all();
        return view("layouts.ajax_layouts.load_subjects", compact("subjects"))->render();
    }

    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 74-ე ხაზზე
     * კითხვების დამატების გვერდზე ყველა გვერდის ჩვენების ფუნქცია
     * @method GET
     */
    public function LoadAllQuestion() {
        $question_s = Question::where("status", 1)->where("deleted", 0)->orderBy("id", "DESC")->paginate(20);
        return view("layouts.ajax_layouts.load_all_questions", compact("question_s"))->render();
    }

    /**
     * ajax მოთხოვნის გაგზავნისას გვერდებად დაყოფის დროს, კონკრეტული გვერდზე დაკლიკებისას
     * ავტომატურად გაიხსნება ახალ გვერდზე არსებული კითხვები, მარშუტი არის routes/web.php ფაილში 59-ე ხაზზე
     * მეთოდის მარშუტი არის routes/web.php ფაილში 76-ე ხაზზე
     * @method GET
     */
    public function Fetch_Questions(Request $request) {
        if($request->ajax()) {
            $question_s = Question::where("status", 1)->orderBy("id", "DESC")->paginate(20);
            return view("layouts.ajax_layouts.load_all_questions", compact("question_s"))->render();   
        }
    }
    
    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 72-ე ხაზზე
     * არააქტიური კითხვების ჩატვირთვის ფუნქცია
     * @method GET
     */
    public function LoadInactives() {
        $inactive_questions = Question::where("status", 0)->orderBy("id", "DESC")->get();
        return view("layouts.ajax_layouts.load_inactive_questions", compact("inactive_questions"))->render();
    }

    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 56-ე ხაზზე
     * კითხვის რედაქტირების გვერდის მეთოდი
     * @method GET
     */
    public function Edit_Question($id, $type) {
        $question = Question::where("id", $id)->where("type", $type)->first();
        $subjects = Subjects::all();
        $history = History::where("question_id", $id)->get();
        return view("edit_pages.edit_question", compact("question", "id", "subjects", "type", "history"));
    }

    /**
     * კითხვების რედაქტირების ჩატვირთვის ფუნქცია, რედაქტირების ხატულაზე დაკლიკებით
     * მეთოდის მარშუტი არის routes/web.php ფაილში 68-ე ხაზზე
     * @method GET
     */
    public function LoadHistory(Request $request) {
        $id = $request->input("questionId"); //  კითხვის ID ნომერი
        $history = History::where("question_id", $id)->get();
        return view("layouts.ajax_layouts.load_history", compact("history"))->render();
    }

    /**
     * დახურული კითხვის დარედაქტირების მეთოდი პოსტ მოთხოვნისას
     * მეთოდის მარშუტი არის routes/web.php ფაილში 58-ე ხაზზე
     * @method POST
     */
    public function EditSingleQuestion($id, Request $request) {
        $this->validate($request, [
            "question" => "required",
            "subject" => "required",
            "a" => "required",
            "b" => "required",
            "c" => "required",
            "d" => "required",
            "correct" => "required",
            "score" => "required",
            "duration" => "required"
        ]);

        try {
            $question = Question::where("id", $id)->first();

            $question->type = "single";
            $question->question = $request->input("question");
            $question->question_subject = $request->input("subject");
            $question->A = $request->input("a");
            $question->B = $request->input("b");
            $question->C = $request->input("c");
            $question->D = $request->input("d");
            $question->correct = $request->input($request->input("correct"));
            $question->score = $request->input("score");
            $question->duration_minute = $request->input("duration");

            $question->save();
            // როდესაც კითხვა დარედაქტირდება ავტომატურად მოხდება ისტორიის შექმნა
            // რომელშიც აისახება თუ ვინ როდის დაარედაქტირა კითხვა
            History::create([
                "question_id" => $id,
                "author" => Auth::user()->name . " " . Auth::user()->lastname
            ]);
            return response()->json(["updated" => 1]);
        }catch(Exception $e) {
            return response()->json(["updated" => 0]);
        }
    }

    /**
     * ღია კითხვის რედაქტირების მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 60-ე ხაზზე
     * @method POST
     */
    public function EditFreeQuestion($id, Request $request) {
        $this->validate($request, [
            "question1" => "required",
            "score1" => "required",
            "subject1" => "required",
            "duration1" => "required",
            "correct_answer_free" => "required"
        ]);

        try {
            Question::where("id", $id)->first()->update([
                "type" => "free",
                "question" => $request->input("question1"),
                "question_subject" => $request->input("subject1"),
                "score" => $request->input("score1"),
                "duration_minute" => $request->input("duration1"),
                "correct" => $request->input("correct_answer_free")
            ]);
            // როდესაც კითხვა დარედაქტირდება ავტომატურად მოხდება ისტორიის შექმნა
            // რომელშიც აისახება თუ ვინ როდის დაარედაქტირა კითხვა
            History::create([
                "question_id" => $id,
                "author" => Auth::user()->name . " " . Auth::user()->lastname
            ]);
            return response()->json(["edited" => 1]);
        }catch(Exception $e) {
            return response()->json(["edited" => 0]);
        }
    }

    /**
     * კითხვის დამატების მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 50-ე ხაზზე
     * @param Illuminate\Http\Request $request
     * @method POST
     * */
    public function Add_Question(Request $request) {
        $this->validate($request, [
            "question" => "required",
            "subject" => "required",
            "a" => "required",
            "b" => "required",
            "c" => "required",
            "d" => "required",
            "correct" => "required",
            "score" => "required",
            "duration" => "required"
        ]);

        try {
            Question::create([
                "type" => "single",
                "question" => $request->input("question"),
                "question_subject" => $request->input("subject"),
                "A" => $request->input("a"),
                "B" => $request->input("b"),
                "C" => $request->input("c"),
                "D" => $request->input("d"),
                "correct" => $request->input($request->input("correct")),
                "score" => $request->input("score"),
                "duration_minute" => $request->input("duration")
            ]);
            return response()->json(["added" => 1]);
        }catch(Exception $e) {
            return response()->json(["added" => 0]);
        }
    }

    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 52-ე ხაზზე
     * ღია კითხვის დამატების მეთოდი
     * @method POST
     */
    public function AddFreeQuestion(Request $request) {
        $this->validate($request, [
            "question1" => "required",
            "score1" => "required",
            "subject1" => "required",
            "duration1" => "required",
            "correct_answer_free" => "required"
        ]);

        try {
            Question::create([
                "type" => "free",
                "question" => $request->input("question1"),
                "question_subject" => $request->input("subject1"),
                "score" => $request->input("score1"),
                "duration_minute" => $request->input("duration1"),
                "correct" => $request->input("correct_answer_free")
            ]);
            return response()->json(["free" => 1]);
        }catch(Exception $e) {
            return response()->json(["free" => 0]);
        }
    }

    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 54-ე ხაზზე
     * რამდენიმე სწორ პასუხიანი კითხვის დამატების მეთოდი
     * @method POST
     */
    public function AddMultipleQuestions(Request $request) {
        $this->validate($request, [
            "question2" => "required",
            "score2" => "required",
            "duration2" => "required",
            "subject2" => "required"
        ]);

        $answers = array(); // მასივი, რომელშიც ჩაიყრება მომხმარებლის მიერ შეტანილი სავარაუდო პასუხები
        $count_answers = count($request->except(["_token", "add_multiple"])) - 4;

        for($i = 1; $i < $count_answers + 1; $i++) {
            // პასუხების მასივში ჩაიყრება შეყვანილი სავარაუდო პასუხები
            array_push($answers, $request->input("answer" . $i));
        }

        $question = new Question();
        $question->type = "multiple";
        $question->question = $request->input("question2");
        $question->question_subject = $request->input("subject2");
        $question->score = $request->input("score2");
        $question->duration_minute = $request->input("duration2");
        $question->answers = $answers;
        $question->corrects = $request->input("corrects");
        $question->save();

        return redirect()->back();
    }

    /**
     * კითხვის წაშლის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 62-ე ხაზზე
     * @method GET
     */
    public function DeleteQuest($id) {
        $question = Question::find($id);
        $question->deleted = 1;
        $question->save();
        return redirect()->back();
    }

    /**
     * თემის დამატების მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 64-ე ხაზზე
     * @return int
     * @method POST
     */
    public function Add_Subject(Request $request) {
        $this->validate($request, [
            "subject_value" => "required"
        ]);

        Subjects::create([
            "subject_name" => $request->input("subject_value")
        ]);

        return 1;
    }

    /**
     * კითხვების pdf/excel ვერსიის გადმოსაწერი ბმულის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 70-ე ხაზზე
     * @method GET
     */
    public function PDF_EXCEL_questions($type, $test_subject) {
        if($type == "pdf") {
            $q = TestsQuestions::where("test_subject", $test_subject)->get();
            $pdf = PDF::loadview("layouts.questions", compact("q", "test_subject"));
            return $pdf->stream("კითხვები.pdf");
        }
    }

    /**
     * ტესტის შედგენის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 78-ე ხაზზე
     * @method GET
     */
    public function MakeTest($test_subject, $w) {
        //დააჯამებს კითხვების ხანგრზლივობის წუთებს რთა განისაზღვროს ამ წუთების საშუალების ტესტირების მთლიანი
        //ხანგრძლივობა
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        $duration = TestsQuestions::where("test_subject", $test_subject)->get()->sum("duration_minute");
        $update_test = Test::where("test_subject", $test_subject)->first(); // კოკრეტულ ტესტთან წვდომა მისი
        //თემატიკის მიხედვით
        $update_test->test_duration = $duration; // ტესტის ხანგრძლივობის განახლება 
        $update_test->save();// მიღებული შედეგის შენახვა
        return view("maketest", compact("test_subject", "w"));
    }

    /**
     * კითხვების ჩვენების მეთოდი ტესტის შედგენისთვის
     * მეთოდის მარშუტი არის routes/web.php ფაილში 82-ე ხაზზე
     * @method POST
     */
    public function ShowQuestion(Request $request) {
        $subject = $request->input("subject");
        $quantity = $request->input("quantity");
        $score = $request->input("score");
        $type = $request->input("type");

        $ts = $request->input("test_subject");
        $ws = $request->input("wrong_score");

        $questions = Question::where("question_subject", $subject)->where("score", $score)->where("type", $type)->limit($quantity)->get();
        $count_questions = $questions->count();
        return view("layouts.ajax_layouts.loadquestion", compact("questions", "ts", "ws", "count_questions", "quantity"))->render();
    }
    //თანამშრომლების ჩვენების მეთოდი ტესტის შედგენისთვის
    // მეთოდის მარშუტი არის routes/web.php ფაილში 84-ე ხაზზე
    public function ShowEmps(Request $request) {
        $department = $request->input("department");
        $service = $request->input("service");
        $position = $request->input("position");

        $employees = Users::where("department", $department)->where("service", $service)->where("position", $position)->get();
        return view("layouts.ajax_layouts.loademp", compact("employees"))->render();
    }

    /**
     * კითხვების მინიჭება კონკრეტულ ტესტზე
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-80 ხაზზე
     * @method POST
     */
    public function AddToTest($test_subject, $w, Request $request) {
        $data = $request->input("questions");

        foreach($data as $dt) {
            $question = Question::where("question", $dt)->get();
            foreach($question as $q) {
                if($q->type == "single") {
                    TestsQuestions::create([
                        "type" => $q->type,
                        "question" => $q->question,
                        "test_subject" => $test_subject,
                        "question_subject" => $q->question_subject,
                        "A" => $q->A,
                        "B" => $q->B,
                        "C" => $q->C,
                        "D" => $q->D,
                        "correct" => $q->correct,
                        "score" => $q->score,
                        "wrong_score" => $w,
                        "duration_minute" => $q->duration_minute
                    ]);
                }else if($q->type == "free") {
                    TestsQuestions::create([
                        "type" => $q->type,
                        "question" => $q->question,
                        "test_subject" => $test_subject,
                        "question_subject" => $q->question_subject,
                        "score" => $q->score,
                        "wrong_score" => $w,
                        "duration_minute" => $q->duration_minute,
                        "correct" => $q->correct
                    ]);
                }else if($q->type == "multiple") {
                    TestsQuestions::create([
                        "type" => $q->type,
                        "question" => $q->question,
                        "test_subject" => $test_subject,
                        "question_subject" => $q->question_subject,
                        "score" => $q->score,
                        "wrong_score" => $w,
                        "duration_minute" => $q->duration_minute,
                        "multi_answers" => $q->answers,
                        "corrects" => $q->corrects
                    ]);
                }
            }
        }

        return back()->with("done_quest", "კითხვები დაემატა");
    }

    /**
     * ტესტის კონკრეტულ თანამშრომლებზე მიმაგრების მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 86-ე ხაზზე
     * @method POST
     */
    public function AssignToEmployee($test_subject, $w, Request $request) {
        $employees_data = $request->input("emps"); // ამ ცვლადში ხდება აპლიკანტების მნიშვნელობების შენახვა

        try {
            foreach($employees_data as $ed) {
                Taken::insert([
                    "user_id" => $ed,
                    "test_subject" => $test_subject
                ]);
                
                /* $email = Users::select("email")->where("id", $ed)->get();
                $mail_body = Auth::user()->name . " " . Auth::user()->lastname . "-(მა) შექმნა ტესტირება თემაზე: " . $test_subject . ". ტესტირების დაწყებისთვის გადადით მოცემულ ლინკზე: http://edu.rda.gov.ge" . "/testing/" . str_replace(" ", "%20", $test_subject);

                foreach($email as $em) {
                    Mail::raw($mail_body, function($message) use($em) {
                        $message->to($em->email);
                        $message->from("davit.chechelashvili@geolab.edu.ge", "RDA ტესტირება");
                        $message->subject("ინფორმაცია ტესტირებაზე");
                    });
                } */
            }
            //ტესტირებისთვის აპლიკანტის მიმაგრებისას პარალელურად შეიქმნება შეტყობინება ტესტის
            //შექმნის თაობაზე და დაგეზავნებათ აპლიკანტებს
            foreach($employees_data as $ed) {
                Notifications::insert([
                    "user_id" => $ed,
                    "type" => "test",
                    "author_avatar" => Auth::user()->avatar,
                    "author_name" => Auth::user()->name,
                    "author_lastname" => Auth::user()->lastname,
                    "content" => Auth::user()->name . " " . Auth::user()->lastname . "-(მა) შექმნა ტესტირება თემაზე: " . $test_subject,
                    "test_subject_for_link" => "/testing/" . $test_subject
                ]);
            }
            return redirect()->back()->with("done_test", "ტესტირება წარმატების გაიგზავნა");
        }catch(Exception $e) {
            return redirect()->back()->with("error_test", "დაფიქსირდა შეცდომა");
        }
    }

    /**
     * შეტყობინებების მუშაობის მთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 88-ე ხაზზე
     * @method GET
     */
    public function Notify() {
        $notifications = Notifications::where("user_id", Auth::user()->id)->where("type", "test")->orderBy("id", "DESC")->get();
        $taken_test = Taken::select("test_subject")->where("user_id", Auth::user()->id)->orderBy("id", "DESC")->get();
        return view("layouts.ajax_layouts.notifies", compact("notifications", "taken_test"))->render();
    }

    /**
     * შეტყობინებების დათვლის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 90-ე ხაზზე
     * @method GET
     */
    public function CountNotify() {
        $notifications = Notifications::where("user_id", Auth::user()->id)->where("type", "test")->where("seen", 0)->get();
        return count($notifications);
    }

    /**
     * ტესტირების გვერდის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 92-ე ხაზზე
     * @method type = GET
     * @method name = Testing()
     * */
    public function Testing($test_subject) {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        $test_info = Test::where("test_subject", $test_subject)->first();
        $submited = SubmitedTests::select("user_id", "test_subject")->first();
        return view("testing", compact("test_info", "submited", "test_subject"));
    }

    /**
     * შეტყობინების წაკითხულად მონიშვნის ფუნქცია
     * მეთოდის მარშუტი არის routes/web.php ფაილში 94-ე ხაზზე
     * @method GET
     */
    public function Seen($id) {
        Notifications::where("user_id", $id)->update([
            "seen" => 1
        ]);
        return "done!";
    }

    /**
     * ტესტირების დაწყების მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 96-ე ხაზზე
     * @method POST
     */
    public function GoTest($test_subject, Request $request) {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        $q = TestsQuestions::where("test_subject", $test_subject)->inRandomOrder()->get();
        $submited = SubmitedTests::select("user_id", "test_subject")->first();
        $test_type = Test::select("type")->where("test_subject", $test_subject)->first(); // წამოიღებს ბაზიდან ტესტის ტიპის მნიშვნელობას
        $test_duration = Test::select("test_duration")->where("test_subject", $test_subject)->first(); // წამოიღებს ბაზიდან ტესტის ხანგრძლივობის მნიშვნელობას
        $request->session()->put("test_type", $test_type->type);
        return view("testing", compact("q", "test_subject", "submited", "test_duration"));
    }

    /**
     * პასუხების შენახვის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 98-ე ხაზზე
     * @method POST
     */
    public function Answer($test_subject, Request $request) {
        $data = json_encode($request->all()); // ტესტის დადასტურების შემდგომ ყველა სეტანილი მონაცემი შეინახება $data ცვლადში
        foreach(json_decode($data) as $dt/*კითხვა*/ => $val/*პასუხი*/) {
            //პასუხების მონიშვნის ან ჩაწერის შემთხვევაში მოხდება მათი ბაზაში გადაგზავნა.
            // ტესტირების გვერდზე ფორმაში კონკრეტული საკითხის ფორმის ველს სახელად აქვს დარქმეული მასივის ტიპის სახელი სადაც მითითებულია უშუალოდ კითხვა და კითხვის აიდი->(მოცემულია "[]"-ფრჩხილებში)
            if($dt == "_token" || $dt == "submit_test") continue;
            // ციკლის საშუალებით უკვე ხდება წვდომა გაცემული პასუხების მნიშვნელობებზე
            // სადაც $question_id ცვლადი არის კითხვის ID ნომერი, და $answer ცვლადში ინახება ჩაწერილი პასუხი
            foreach($val as $question_id => $answer) {
                $answers = []; // აპლიკანტის მიერ გაცემული სწორი პასუხები ასარჩევ კითხვებზე ჩაიყრება მოცემულ მასივში
                // ქვემოთ მოცემული ციკლის საშუალებით
                if(is_countable($answer)) {
                    for($i = 0; $i < count($answer); $i++) {
                        array_push($answers, $answer[$i]);
                    }
                    // დადასტურებული მონაცემების ბაზაში შენახვა
                    Answer::insert([
                        "user_id" => Auth::user()->id, // აპლიკანტის აიდი
                        "user_name" => Auth::user()->name, // აპლიკანტის სახელი
                        "user_lastname" => Auth::user()->lastname, // აპლიკანტის გვარი
                        "test_subject" => $test_subject, // ტესტის სახელი/თემატიკა
                        "question" => $dt, // კითხვა
                        "tquestion_id" => $question_id, // კითხვის აიდი
                        "answers" => json_encode($answers) // ასარჩევი კითხვის პასუხები
                    ]);
                }else {
                    Answer::insert([
                        "user_id" => Auth::user()->id, // აპლიკანტის აიდი
                        "user_name" => Auth::user()->name, // აპლიკანტის სახელი
                        "user_lastname" => Auth::user()->lastname, // აპლიკანტის გვარი
                        "test_subject" => $test_subject, // ტესტის სახელი/თემატიკა
                        "question" => $dt, // კითხვა
                        "answer" => $answer, // პასუხი
                        "tquestion_id" => $question_id // კითხვის აიდი
                    ]);
                }
            }
        }

        SubmitedTests::create([
            "user_id" => Auth::user()->id,
            "test_subject" => $test_subject,
            "type" => $request->session()->get("test_type")
        ]);
        return redirect("/home/1");
    }

    /**
     * აპლიკანტთა სია ვინც წერა ტესტირება
     * მეთოდის მარშუტი არის routes/web.php ფაილში მე-100 ხაზზე
     * @method GET
     */
    public function TestsResult() {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        $users = Users::select("users.id", "users.name", "users.lastname", "submited_tests.test_subject")
                    ->join("submited_tests", "users.id", "=", "submited_tests.user_id")
                    ->where("submited_tests.type", "test")
                    ->distinct()
                    ->get();
        return view("results", compact("users"));
    }

    /**
     * დალოგინებული მომხმარებლისთვის ტესტირების შედეგების გვერდის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 106-ე ხაზზე
     * @method GET
     */
    public function UserTestResult() {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        $data = SubmitedTests::where("user_id", auth()->user()->id)->where("submited_tests.type", "test")->orderBy("id", "DESC")->get();
        return view("utest_results", compact("data"));
    }

    /**
     * ტესტის შედეგის პედეეფში დაგენერირება მომხმარებლის მხარეს
     * მეთოდის მარშუტი არის routes/web.php ფაილში 108-ე ხაზზე
     * @method GET
     */
    public function UpdfRes($uid, $utest) {
        $uresult = Answer::where("user_id", $uid)->where("test_subject", $utest)->distinct()->get();
        $header_data = Answer::where("user_id", $uid)->where("test_subject", $utest)->first();

        $pdf = PDF::loadview("layouts.user_result", compact("uresult", "header_data"));
        return $pdf->stream("ტესტირების_შედეგი.pdf");
    }

    /**
     * ტესტის შედეგის პედეეფში დაგენერირება ეიჩარისა და უფროსობის მხარეს
     * სადაც უკვე გამოჩნდება კითხვები თავისი პასუხებითაც და შეფასებული ქულებითაც
     * მეთოდის მარშუტი არის routes/web.php ფაილში 110-ე ხაზზე
     * @method GET
     */
    public function UpdfResHR($uid, $utest) {
        $header_data = Answer::where("user_id", $uid)->where("test_subject", $utest)->first();

        $uresult = DB::select("SELECT tquestions.id, tquestions.type, tquestions.question, tquestions.correct, tquestions.score, tquestions.wrong_score, 
                                      answers.answer, answers.question, tquestions.multi_answers, answers.answers, tquestions.corrects 
                                      FROM tquestions INNER JOIN answers 
                                      ON tquestions.id = answers.tquestion_id
                                      WHERE answers.test_subject = '$utest' AND answers.user_id = $uid ORDER BY tquestions.id");

        $answers_sum = Answer::select("score")->where("test_subject", $utest)->get()->sum("score");
        //pdf-ში დაგენერირებული გვერდი მოცემულია view/layouts/users_result_hr.blade.php ფაილში
        $pdf = PDF::loadview("layouts.users_result_hr", compact("uresult", "header_data", "answers_sum"));
        return $pdf->stream($header_data->user_name . "_" . $header_data->user_lastname . ".pdf");
    }

    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 112-ე ხაზზე
     * გამოკითხვის შედეგების გვერდი პდფ-ში
     * @method GET
     */
    public function QuizPDF($uid, $utest) {
        $uresult = Answer::where("user_id", $uid)->where("test_subject", $utest)->distinct()->get();
        $header_data = Answer::where("user_id", $uid)->where("test_subject", $utest)->first();

        $pdf = PDF::loadview("layouts.quiz_result", compact("uresult", "header_data"));
        return $pdf->stream($header_data->test_subject . ".pdf");
    }

    /**
     * პასუხების გადამოწმების გვერდის მეთოდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 102-ე ხაზზე
     * @method GET
     */
    public function Check($user_id, $test_subject) {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        $answers = TestsQuestions::selectRaw("tquestions.id, tquestions.type, tquestions.question, tquestions.correct, tquestions.correct, answers.user_id, tquestions.score, tquestions.wrong_score, answers.answer, answers.question")->join("answers", "tquestions.id", "=", "answers.tquestion_id")->where("tquestions.test_subject", $test_subject)->distinct()->get();
        return view("checkanswer", compact("answers", "user_id"));
    }

    /**
     * პასუხის მონიშვნის გვერდი სადაც უკვე ენიჭება ქულა (სწორია თუ არასწორია)
     * მეთოდის მარშუტი არის routes/web.php ფაილში 104-ე ხაზზე
     * @method POST
     */
    public function CheckFinal($corr, $uid, Request $request) {
        $aans = $request->input("aanswer");
        $question = str_replace("_", " ", $request->input("qquestion"));
        
        if($corr == "yes") {
            try {
                $score = TestsQuestions::select("score")->where("question", $question)->first();
                Answer::where("answer", $aans)->where("user_id", $uid)->update([
                    "score" => $score->score,
                    "answer_score" => $score->score
                ]);
                return response()->json(["status_corr" => 1]);
            }catch(Exception $e) {
                return response()->json(["status_corr" => 0]);
            }
        }else if($corr == "not") {
            try {
                $wrongscore = TestsQuestions::select("wrong_score", "score")->where("question", $question)->first();
                Answer::where("answer", $aans)->where("user_id", $uid)->update([
                    "score" => $wrongscore->wrong_score,
                    "answer_score" => $wrongscore->score
                ]);
                return response()->json(["status_wrong" => 1]);
            }catch(Exception $e) {
                return response()->json(["status_wrong" => 0]);
            }
        }
    }
     
    /**
     * გამოკითხვების შედეგების გვერდი
     * მეთოდის მარშუტი არის routes/web.php ფაილში 114-ე ხაზზე
     * @method GET
     */
    public function Quiz() {
        if(!Auth::check()) return redirect("/"); // თუ მომხმარებელი არ იქნება სისტემაში შესული, გვერდი არ ჩაიტვირთება და გადაამისამართებს ავტორიცაზიის/ლოგინის გვერდზე
        $users = Users::select("users.id", "users.name", "users.lastname", "submited_tests.test_subject")
                    ->join("submited_tests", "users.id", "=", "submited_tests.user_id")
                    ->where("submited_tests.type", "quiz")
                    ->distinct()
                    ->get();
        return view("quiz", compact("users"));
    }

    //==============================კითხვის აქტიურობების განსაზღვრა=============
    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 117-ე ხაზზე
     * @method GET
     */
    public function Active(Request $request) { //აქტიურზე დაყენება
        $id = $request->input("id");
        Question::where("id", $id)->first()->update([
            "status" => 1
        ]);
        return response()->json(["changed" => 1]);
    }

    /**
     * მეთოდის მარშუტი არის routes/web.php ფაილში 118-ე ხაზზე
     * @method 118
     */
    public function InActive(Request $request) { //არააქტიურზე დაყენება
        $id = $request->input("id");
        Question::where("id", $id)->first()->update([
            "status" => 0
        ]);
        return response()->json(["changed" => 1]);
    }
    //==============================კითხვის აქტიურობების განსაზღვრა=============
    
    /**
     * კითხვის დაძებნის მეთოდი ajax მოთხოვნისთვის
     * მეთოდის მარშუტი არის routes/web.php ფაილში 121-ე ხაზზე
     * @method GET
     */
    public function Live_Search_Question(Request $request) {
        $value = $request->input("value"); // ძებნის ველში ცაწერილი მნიშვნელობა
        // თუ სერვერზე მოხდება ajax ტიპის მოთხოვნა შესრულდება მოცემული კოდი
        if($request->ajax()) {
            if($value != "") {
                $question_s = Question::where("question", "like", "%" . $value . "%")
                    ->orWhere("question", "like", $value . "%") // ფილტრი კითხვისთვის
                    ->orWhere("question", "like", "%" . $value) // ფილტრი კითხვისთვის
                    ->orWhere("question_subject", "like", "%" . $value . "%") // ფილტრი კითხვის თემისთვის
                    ->orWhere("question_subject", "like", $value . "%") // ფილტრი კითხვის თემისთვის
                    ->orWhere("question_subject", "like", "%" . $value) // ფილტრი კითხვის თემისთვის
                    ->orWhere("type", "like", "%" . $value . "%") // ფილტრი კითხვის ტიპისთვის
                    ->orWhere("type", "like", $value . "%") // ფილტრი კითხვის ტიპისთვის
                    ->orWhere("type", "like", "%" . $value) // ფილტრი კითხვის ტიპისთვის
                    ->orWhere("A", "like", "%" . $value . "%") // ფილტრი (ა) ვარიანტი პასუხისთვის
                    ->orWhere("A", "like", $value . "%") // ფილტრი (ა) ვარიანტი პასუხისთვის
                    ->orWhere("A", "like", "%" . $value) // ფილტრი (ა) ვარიანტი პასუხისთვის
                    ->orWhere("B", "like", "%" . $value . "%") // ფილტრი (ბ) ვარიანტი პასუხისთვის
                    ->orWhere("B", "like", $value . "%") // ფილტრი (ბ) ვარიანტი პასუხისთვის
                    ->orWhere("B", "like", "%" . $value) // ფილტრი (ბ) ვარიანტი პასუხისთვის
                    ->orWhere("C", "like", "%" . $value . "%") // ფილტრი (გ) ვარიანტი პასუხისთვის
                    ->orWhere("C", "like", $value . "%") // ფილტრი (გ) ვარიანტი პასუხისთვის
                    ->orWhere("C", "like", "%" . $value) // ფილტრი (გ) ვარიანტი პასუხისთვის
                    ->orWhere("D", "like", "%" . $value . "%") // ფილტრი (დ) ვარიანტი პასუხისთვის
                    ->orWhere("D", "like", $value . "%") // ფილტრი (დ) ვარიანტი პასუხისთვის
                    ->orWhere("D", "like", "%" . $value) // ფილტრი (დ) ვარიანტი პასუხისთვის
                    ->orWhere("correct", "like", "%" . $value . "%") // ფილტრი სწორი პასუხისთვის
                    ->orWhere("correct", "like", $value . "%") // ფილტრი სწორი პასუხისთვის
                    ->orWhere("correct", "like", "%" . $value) // ფილტრი სწორი პასუხისთვის
                    ->paginate(40); // მოხდება გვერდებად დაყოფა და თითო ვერდზე განთავსდება მაქსიმუმ 40 ჩანაწერი
                return view("layouts.ajax_layouts.load_all_questions", compact("question_s"))->render();
            }else {
                $question_s = Question::where("status", 1)->orderBy("id", "DESC")->paginate(20);
                return view("layouts.ajax_layouts.load_all_questions", compact("question_s"))->render();
            }
        }
    }

    /**
     * მარშუტი არის routes/web.php ფაილში 123-ე ხაზზე
     * გვერდებად დაყოფილი ტესტების მეთოდი, რომლის გვერდზეც დაკლიკებისას გაიხსნება ახალი გვერდი
     * @method GET 
     */
    public function Fetch_Tests(Request $request) {
        if($request->ajax()) {
            $tests = Test::orderBy("id", "DESC")->paginate(30);
            return view("layouts.ajax_layouts.tests", compact("tests"))->render();
        }
    }
    
    /**
     * მარშუტი არის routes/web.php ფაილში 125-ე ხაზზე
     * ტესტების ძებნის მეთოდი, რომელიც სრულდება ajax მოთხოვნისას
     * @method GET
     */
    public function Search_Tests(Request $request) {
        $search_value = $request->input("value");
        if($request->ajax()) {
            if($search_value != "") {
                $tests = Test::where("test_subject", "like", "%" . $search_value . "%") // ფილტრი ტესტის თემისთვის
                    ->orWhere("test_subject", "like", $search_value . "%") // ფილტრი ტესტის თემისთვის
                    ->orWhere("test_subject", "like", "%" . $search_value) // ფილტრი ტესტის თემისთვის
                    ->orWhere("test_date", "like", "%" . $search_value . "%") // ფილტრი ტესტის ჩატარების ტარიღისთვის
                    ->orWhere("test_date", "like", "%" . $search_value) // ფილტრი ტესტის ჩატარების ტარიღისთვის
                    ->orWhere("test_date", "like", $search_value . "%") // ფილტრი ტესტის ჩატარების ტარიღისთვის
                    ->orWhere("test_start_time", "like", "%" . $search_value . "%") // ფილტრი დაწყების დროისთვის
                    ->orWhere("test_start_time", "like", "%" . $search_value) // ფილტრი დაწყების დროისთვის
                    ->orWhere("test_start_time", "like", $search_value . "%") // ფილტრი დაწყების დროისთვის
                    ->orWhere("test_duration", "like", "%" . $search_value . "%") // ფილტრი ხანგრძლივობისთვის
                    ->orWhere("test_duration", "like", "%" . $search_value) // ფილტრი ხანგრძლივობისთვის
                    ->orWhere("test_duration", "like", $search_value . "%") // ფილტრი ხანგრძლივობისთვის
                    ->orWhere("wrong_score", "like", "%" . $search_value . "%") // ფილტრი არასწორი ქულისთვის
                    ->orWhere("wrong_score", "like", "%" . $search_value) // ფილტრი არასწორი ქულისთვის
                    ->orWhere("wrong_score", "like", $search_value . "%") // ფილტრი არასწორი ქულისთვის
                    ->paginate(30);
                return view("layouts.ajax_layouts.tests", compact("tests"))->render();
            }else {
                $tests = Test::orderBy("id", "DESC")->paginate(30);
                return view("layouts.ajax_layouts.tests", compact("tests"))->render();
            }
        }
    }

    /**
     * თანამშრომელთა ჩატვირთვა/დარენდერების მეთოდი
     * მარშუტი არის routes/web.php ფაილში 127-ე ხაზზე
     * @method GET
     */
    public function Load_Employee() {
        $users = Users::paginate(24);
        return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
    }

    /**
     * თანამშრომელთა ძებნის მეთოდი ajax მოთხოვნისთვის
     * მარშუტი არის routes/web.php ფაილში 129-ე ხაზზე 
     * @method GET
     */
    public function Search_Employee(Request $request) {
        $search_value = $request->input("value");
        if($request->ajax()) {
            if($search_value != "" || $search_value != null) {
                $users = Users::where("name", "like", "%" . $search_value . "%")
                        ->orWhere("name", "like", $search_value . "%")
                        ->orWhere("name", "like", "%" . $search_value)
                        ->orWhere("lastname", "like", $search_value . "%")
                        ->orWhere("lastname", "like", "%" . $search_value)
                        ->orWhere("lastname", "like", "%" . $search_value . "%")
                        ->orWhere("pid", "like", $search_value . "%")
                        ->orWhere("pid", "like", "%" . $search_value)
                        ->orWhere("pid", "like", "%" . $search_value . "%")
                        ->orWhere("phone", "like", $search_value . "%")
                        ->orWhere("phone", "like", "%" . $search_value)
                        ->orWhere("phone", "like", "%" . $search_value . "%")
                        ->orWhere("service", "like", $search_value . "%")
                        ->orWhere("service", "like", "%" . $search_value)
                        ->orWhere("service", "like", "%" . $search_value . "%")
                        ->orWhere("position", "like", $search_value . "%")
                        ->orWhere("position", "like", "%" . $search_value)
                        ->orWhere("position", "like", "%" . $search_value . "%")
                        ->orWhere("department", "like", $search_value . "%")
                        ->orWhere("department", "like", "%" . $search_value)
                        ->orWhere("department", "like", "%" . $search_value . "%")
                        ->orWhere("email", "like", $search_value . "%")
                        ->orWhere("email", "like", "%" . $search_value)
                        ->orWhere("email", "like", "%" . $search_value . "%")
                        ->orWhere(DB::raw("CONCAT(name, ' ', lastname)"), "like", "%" . $search_value . "%")
                        ->paginate(24);
                return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
            }else {
                $users = Users::paginate(24);
                return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
            }
        }
    }

    /**
     * თანამშრომელთა ჩატვირთვა/დარენდერების მეთოდი ajax-ის გამოყენებით, გვერდების ნომერზე დაკლიკებისას
     * მარშუტი არის routes/web.php ფაილში 131-ე ხაზზე
     * @method GET
     */
    public function Fetch_Employees(Request $request) {
        if($request->ajax()) {
            $users = Users::paginate(24);
            return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
        }
    }

    /**
     * თანამშრომელთა ფილტრაციის მეთოდი ajax-ის გამოყენებით
     * მარშუტი არის routes/web.php ფაილში 133-ე ხაზზე
     * @method GET
     */
    public function Filter_Employee(Request $request) {
        $department = $request->input("department"); // დეპარტმანეტის მნიშვნელობა ფორმის ველში გაგზავნისას
        $service = $request->input("service"); // სამსახურის მნიშვნელობა ფორმის ველში გაგზავნისას
        $position = $request->input("position"); // პოზიციის  მნიშვნელობა ფორმის ველში გაგზავნისას

        if($request->ajax()) {
            // თანამშრომლის ჩატვირთვა დეპარტამენტის მიხედვით
            if(!empty($department) && empty($service) && empty($position)) {
                $users = Users::where("department", $department)->paginate(40);
                return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
            }
            // თანამშრომლებიც ფილტრაცია სამსახურების მიხედვით
            if(empty($department) && !empty($service) && empty($position)) {
                $users = Users::where("service", $service)->paginate(40);
                return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
            }
            // თანამშრომლებიც ფილტრაცია პოზიციების მიხედვით
            if(empty($department) && empty($service) && !empty($position)) {
                $users = Users::where("position", $position)->paginate(40);
                return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
            }
            // თანამშრომლების ფილტრაცია სამივე კრიტერიუმის მიხედვით
            if(!empty($department) && !empty($service) && !empty($position)) {
                $users = Users::where("position", $position)->where("department", $department)->where("service", $service)->paginate(40);
                return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
            }
            // თანამშრომლების გაფილტვრა პოზიციებისა და სამსახურების მიხედვით
            if(empty($department) && !empty($service) && !empty($position)) {
                $users = Users::where("position", $position)->where("service", $service)->paginate(40);
                return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
            }
            // თანამშრომლების გაფილტვრა პოზიციებისა და დეპარტამენტების მიხედვით
            if(!empty($department) && empty($service) && !empty($position)) {
                $users = Users::where("position", $position)->where("department", $department)->paginate(40);
                return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
            }
            // თანამშრომლების გაფილტვრა სამსახურების და დეპარტამენტების მიხედვით
            if(!empty($department) && !empty($service) && empty($position)) {
                $users = Users::where("service", $service)->where("department", $department)->paginate(40);
                return view("layouts.ajax_layouts.load_employees")->with("users", $users)->render();
            }
        }
    }

    /**
     * წაშლილი კითხვების დარენდერების/ჩატვირთვის მეთოდი
     * მარსუტი არის routes/web.php ფაილში 135-ე ხაზზე
     * @method GET
     */
    public function LoadDeletedQuestions() {
        $question_s = Question::where("deleted", 1)->orderBy("id", "DESC")->get();
        return view("layouts.ajax_layouts.load_deleted_questions", compact("question_s"))->render();
    }

    /**
     * კითხვის საბოლოოდ წაშლის მეთოდი
     * მარშუტი არის routes/web.php ფაილში 137-ე ხაზზე
     * @method GET
     */
    public function DeleteQ($id) {
        Question::find($id)->delete();
        return back();
    }

    /**
     * კითხვების ფილტრაციის მეთოდი ajax-ის გამოყენებით
     * მარშუტი არის routes/web.php ფაილში 139-ე ხაზზე
     * @method GET
     */
    public function Filter_Questions(Request $request) {
        $subject = $request->input("subject"); // კითხვის თემატიკა
        $type = $request->input("qtype"); // კითხვის ტიპი
        $wscore = $request->input("wscore"); // კითხვის შეფასების ქულა არასწორი პასუხისას
        $score = $request->input("f_score"); // სწორი პასუხის ქულა
        $duration = $request->input("f_duration"); // კითხვის ხანგრძლივობა
        // ფილტრაცია იმოქმედებს მაშინ თუ მოთხოვნა იქნება ajax-ის საშუალებით გაგზავნილი
        if($request->ajax()) {
            if(!empty($subject) && empty($type) && empty($wscore) && empty($score) && empty($duration)) {
                // აქ ხდება ფილტრაცია კითხვის თემატიკის მიხედვით, თუ დანარჩენი ველები იქნება ცარიელი
                $question_s = Question::where("question_subject", "like", "%" . $subject . "%")
                ->orWhere("question_subject", "like", "%" . $subject)
                ->orWhere("question_subject", "like", $subject . "%")->paginate(40);
                return view("layouts.ajax_layouts.load_all_questions", compact("question_s"))->render();
            }else if(empty($subject) && !empty($type) && empty($wscore) && empty($score) && empty($duration)) {
                // აქ ხდება ფილტრაცია კითხვის ტიპის მიხედვით, თუ დანარჩენი ველები იქნება ცარიელი
                $question_s = Question::where("type", "like", "%" . $type . "%")
                ->orWhere("type", "like", "%" . $type)
                ->orWhere("type", "like", $type . "%")->paginate(40);
                return view("layouts.ajax_layouts.load_all_questions", compact("question_s"))->render();
            }else if(empty($subject) && empty($type) && !empty($wscore) && empty($score) && empty($duration)) {
                // აქ ხდება ფილტრაცია კითხვის არასწორი პასუხის ქულის მიხედვით, თუ დანარჩენი ველები იქნება ცარიელი
                $question_s = Question::where("wrong_score", "like", "%" . $wscore . "%")
                ->orWhere("wrong_score", "like", "%" . $wscore)
                ->orWhere("wrong_score", "like", $wscore . "%")->paginate(40);
                return view("layouts.ajax_layouts.load_all_questions", compact("question_s"))->render();
            }else if(empty($subject) && empty($type) && !($wscore) && !empty($score) && empty($duration)) {
                // აქ ხდება ფილტრაცია კითხვის წონის/ქულის მიხედვით, თუ დანარჩენი ველები იქნება ცარიელი
                $question_s = Question::where("score", "like", "%" . $score . "%")
                ->orWhere("score", "like", "%" . $score)
                ->orWhere("score", "like", $score . "%")->paginate(40);
                return view("layouts.ajax_layouts.load_all_questions", compact("question_s"))->render();
            }else if(empty($subject) && empty($type) && !($wscore) && empty($score) && !empty($duration)) {
                // აქ ხდება ფილტრაცია კითხვის ხანგრძლივობის მიხედვით, თუ დანარჩენი ველები იქნება ცარიელი
                $question_s = Question::where("duration_minute", "like", "%" . $duration . "%")
                ->orWhere("duration_minute", "like", "%" . $duration)
                ->orWhere("duration_minute", "like", $duration . "%")->paginate(40);
                return view("layouts.ajax_layouts.load_all_questions", compact("question_s"))->render();
            }
        }
    }

    /**
     * მარშუტი არის routes/web.php ფაილში 141-ე ხაზზე
     * კითხვების დათვლის მეთოდი, გამოჩნდება კონკრეტულ თემატიკაში რამდენი აქტიური და არააქტიური კითხვაა
     * @method GET
     */
    public function Count_Questions($subject, Request $request) {
        if($request->ajax()) {
            // მოცემული კოდი შესრულდება სერვერზე ajax მოთხოვნის გაგზავნისას
            $active_question = Question::where("question_subject", $subject)->where("status", 1)->get()->count();
            $inactive_question = Question::where("question_subject", $subject)->where("status", 0)->get()->count();
            // მოხდება პასუხის დაბრუნება json ფორმატში
            return response()->json([
                "actives" => $active_question, // აქტიური კითხვების რაოდენობა
                "inactives" => $inactive_question // არააქტიური კითხვების რაოდენობა
            ]);
        }
    }

    /**
     * მარშუტი არის routes/web.php ფაილში 143-ე ხაზზე
     * მოცემული მეთოდის საშუალებით ხდება ყველა არააქტიური კითხვის გააქტიურება ajax მოთხოვნით
     * @method GET
     */
    public function SetAllActive(Request $request) {
        if($request->ajax()) {
            Question::query()->update([
                "status" => 1
            ]);
        }
    }

    /**
     * მარშუტი არის routes/web.php ფაილში 145-ე ხაზზე
     * მოცემული მეთოდის საშუალებით ხდება ყველა აქტიური გახდება არააქტიური ajax მოთხოვნით
     * @method GET
     */
    public function SetAllInActive(Request $request) {
        if($request->ajax()) {
            Question::query()->update([
                "status" => 0
            ]);
        }
    }

    /**
     * ტესტირების შედეგბეის გვერდებად დაყოფის მეთოდი
     * მარშუტი არის routes/web.php ფაილში 147-ე ხაზზე
     * @method GET
     */
    public function LoadTestResults(Request $request) {
        if($request->ajax()) {
            $users = Users::select("users.id", "users.name", "users.lastname", "submited_tests.test_subject")
                    ->join("submited_tests", "users.id", "=", "submited_tests.user_id")
                    ->where("submited_tests.type", "test")
                    ->distinct()
                    ->paginate(40);
            return view("layouts.ajax_layouts.load_test_results_to_hr", compact("users"))->render();
        }
    }

    /**
     * ტესტირების სედეგბეის ჩატვირთვის მეთოდი გვერდის ნომერზე დაკლიკებით
     * მარშუტი არის routes/web.php ფაილში 149-ე ხაზზე
     * @method GET
     */
    public function Fetch_Results(Request $request) {
        if($request->ajax()) {
            $users = Users::select("users.id", "users.name", "users.lastname", "submited_tests.test_subject")
                    ->join("submited_tests", "users.id", "=", "submited_tests.user_id")
                    ->where("submited_tests.type", "test")
                    ->distinct()
                    ->paginate(40);
            return view("layouts.ajax_layouts.load_test_results_to_hr", compact("users"))->render();
        }
    }

    /**
     * ასარჩევ პასუხებიანი კითხვის დარედაქტირების მეთოდი
     * მარშუტი არის routes/web.php ფაილში 151-ე ხაზზე
     * @method POST
     */
    public function EditMultiple(Request $request) {
        $question = Question::find($request->input("qid"));

        $answers = []; // აქ ჩაიყრება ფორმიდან გაგზავნილი სავარაუდო პასუხები
        $corrects = []; // აქ ჩაიყრება ფორმიდან გაგზავნილი სწორი პასუხები

        foreach($request->answers as $anss) {
            if(is_null($anss) || empty($anss)) continue;
            array_push($answers, $anss);
        }

        foreach($request->corrects as $corrs) {
            if(is_null($corrs) || empty($corrs)) continue;
            array_push($corrects, $corrs);
        }

        $question->duration_minute = $request->input("duration2");
        $question->score = $request->input("score2");
        $question->question_subject = $request->input("subject2");
        $question->answers = $answers;
        $question->corrects = $corrects;
        $question->save();
        return redirect()->back();
    }
}