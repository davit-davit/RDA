<?php
    Route::get("/", "MainController@Login"); // ავტორიზაციის მარშუტი
    Route::post("/", "MainController@Login_Valid"); // ავტორიზაციის დროს ვალიდაციის მარშუტი

    Route::post("/logout", "MainController@Logout")->name("logout"); // სისტემიდან გამოსვლის "logout" -ის ლინკ(მარშუტი)

    Route::get("/reset_password", "MainController@ResetPass"); // პაროლის აღდგენის მარშუტი
    
    Route::post("/reset_password", "MainController@Reset_Validate"); // პაროლის აღდგენის ვალიდაციის მარშუტი

    Route::get("/home/{t?}", "MainController@Home")->name("homepage")->where("t", "[0-9]+"); // სისტემის მთავარი მარშუტი

    Route::get("/edit_profile", "MainController@EditProfile"); // პროფილის რედაქტირების მარშუტი

    Route::post("/change_pass", "MainController@Password_Change"); // პაროლის შეცვლის მარშუტი
    
    Route::post("/change_phone", "MainController@Phone_Change"); // ტელეფონის ცვლილების მარშუტი
    
    Route::post("/change_avatar", "MainController@Change_Photo"); // ავატარის ცვლილების მარშუტი

    Route::get("/employees", "MainController@Employees"); //თანამშრომლების გვერდის მარშუტი

    Route::get("/delete_emp/{id}", "MainController@DeleteEmp")->where("id", "[0-9]+"); // თანამშრომლის წაშლის მარშუტი
    
    Route::get("/add_emp", "MainController@AddEmp"); // თანამშრომლის დამატების მარშუტი

    Route::post("/add_emp", "MainController@Emp_Valid"); // თანამშრომლის დამატების მარშუტი ატვირთვის

    Route::get("/edit_emp/{id}", "MainController@Edit")->where("id", "[0-9]+"); // თანამშრომლის დარედაქტირების მარშუტი

    Route::post("/edit_emp/{id}", "MainController@EdEmp")->where("id", "[0-9]+"); // თანამსრომლის რედაქტირების მარშუტი POST მოთხოვნისას

    Route::get("/reset", "MainController@PReset"); // პაროლის აღდგენის მარშუტი, რომელ გვერდზეც 
    // ჩაიწერება აღმდგენის იმეილი და კოდი, რომელიც მიუვა იმეილზე

    Route::post("/reset", "MainController@P_Reset");// პაროლის აღდგენის მარშუტი "პოსტ" მოთხოვნისას

    Route::get("/create_test", "MainController@Create_Test"); //ტესტის შედგენის მარშუტი
    
    Route::post("/create_test", "MainController@CreateTest"); //ტესტის შედგენის მარშუტი post მოთხოვნისას
    
    Route::get("/showtests", "MainController@ShowTests"); // ტესტების გამოცენის მარშუტი ajax-ის გამოყენებით

    Route::get("/delete_test/{id}", "MainController@Delete_Test")->where("id", "[0-9]+"); //ტესტის წაშლის მარშუტი
    
    Route::get("/add_question", "MainController@AddQuestion"); //კითხვარის დამატების მარშუტი
    
    Route::get("/questions", "MainController@Question_s"); //კითხვების გვერდის მარშუტი

    Route::post("/add_question", "MainController@Add_Question"); //დახურული კითხვის დამატების მარშუტი
    
    Route::post("/add_question1", "MainController@AddFreeQuestion");//ღია კითხვის დამატების მარშუტი
    
    Route::post("/add_multiple", "MainController@AddMultipleQuestions"); // ასარჩევ პასუხებიანი კითხვის დამატების მარშუტი

    Route::get("/edit_question/{id}/{type}", "MainController@Edit_Question")->where("id", "[0-9]+"); // კითხვის რედაქტირების გვერდის მარშუტი
    
    Route::post("/edit_question/{id}", "MainController@EditSingleQuestion")->where("id", "[0-9]+"); // დახურული კითხვის რედაქტირების მარშუტი POST მოთხოვნისას
    
    Route::post("/edit_question1/{id}", "MainController@EditFreeQuestion")->where("id", "[0-9]+"); // ღია კითხვის რედაქტირების მარშუტი POST მოთხოვნისას

    Route::get("/delete_question/{id}", "MainController@DeleteQuest")->where("id", "[0-9]+"); //კითხვის წაშლის მარშუტი

    Route::post("/subject_add", "MainController@Add_Subject"); //თემის დამატების მარშუტი

    Route::get("/load_subjects", "MainController@LoadSubjects"); //კითხვის თემატიკების ავტომატურად განახლების მარშუტი ajax ის გამოყენებით
    
    Route::get("/load_history", "MainController@LoadHistory"); // კითხვის ისტორიების ჩატვირთვა ajax -ით
    
    Route::get("/get_pdf_or_excel/{type}/{test_subject}", "MainController@PDF_EXCEL_questions"); // კითხვების გადმოსაწერი მარშუტი
    
    Route::get("/load_inactives", "MainController@LoadInactives"); // არააქტიური კითხვების ajax-ით ჩატვირთვის მარშუტი
    
    Route::get("/load_all_question", "MainController@LoadAllQuestion"); // ყველა ატვირთული აქტიური კითხვის ჩვენების მარშუტი კითხვის დამატების გვერდზე ajax-ით
    
    Route::get("/fetch_data", "MainController@Fetch_Questions"); // გვერდებად დანომრილი კითხვების, ახალ გვერდზე გამოჩენის მარშუტი ajax მოთხოვნის გაგზავნისას, კონკრეტულ გვერდზე დაკლიკებისას

    Route::get("/maketest/{test_subject}/{w}", "MainController@MakeTest"); //ტესტის შედგენის მარშუტი
    
    Route::post("/addtotest/{test_subject}/{w}", "MainController@AddToTest"); //ტესტის შედგენის მარშუტი, პოსტ მოთხოვნისას

    Route::post("/show_question", "MainController@ShowQuestion"); // კითხვების ჩვენების მარშუტი ეიჯექს მოთხოვნის დროს
    
    Route::post("/show_emps", "MainController@ShowEmps"); // თანამშრომლების ჩვენების მარშუტი ეიჯექს მოთხოვნისას

    Route::post("/maketest/{test_subject}/{w}", "MainController@AssignToEmployee"); // ტესტის თანამშრომელზე მიმაგრების მარშუტი

    Route::get("/load_notify", "MainController@Notify"); //შეტყობინების ჩვენების მარშუტი
    
    Route::get("/count_notify", "MainController@CountNotify"); //შეტყობინების დათვლის მარშუტი

    Route::get("/testing/{test_subject}", "MainController@Testing"); //ტესტირების გვერდის მარშუტი

    Route::get("/seen/{id}", "MainController@Seen")->where("id", "[0-9]+"); // შეტყობინების წაკითხულად მონიშვნის მარშუტი

    Route::post("/testing/{test_subject}", "MainController@GoTest"); //ტესტირების გვერდის მარშუტი
    
    Route::post("/answer/{test_subject}", "MainController@Answer"); //პასუხების პარშუტი

    Route::get("/test_result", "MainController@TestsResult"); //ტესტირების შედეგების გვერდი
    
    Route::get("/check_test/{id}/{test_subject}", "MainController@Check"); //პასუხების შემოწმების გვერდი

    Route::post("/checkfinal/{correction}/{uid}", "MainController@CheckFinal"); //პასუხების შემოწმების მარშუტი
    
    Route::get("/user_test_result", "MainController@UserTestResult"); // დალოგინებული მომხმარებლის ტესტების შედეგების გვერდი

    Route::get("/pdfresult/{id}/{test_subject}", "MainController@UpdfRes"); //აპლიკანტის ტესტირების შედეგის ნახვა pdf ფაილში
    
    Route::get("/pdfresulthr/{id}/{test_subject}", "MainController@UpdfResHR"); //აპლიკანტის ტესტირების შედეგის ნახვა pdf ფაილში(მხოლოდ ეიჩარებისთვის)
    
    Route::get("/quizresult/{id}/{test_subject}", "MainController@QuizPDF"); //გამოკითხვების შედეგები პდფ-ში
    
    Route::get("/quiz", 'MainController@Quiz'); // გამოკითხვის შედეგების გვერდი

    //==========================================================================
    Route::get("/set_active", "MainController@Active"); //აქტიურ სტატუსზე დაყენება
    Route::get("/set_inactive", "MainController@InActive"); //არააქტიურ სტატუსზე დაყენება
    //==========================================================================

    Route::get("/questions_live_search", "MainController@Live_Search_Question"); // კითხვის ძებნის მარშუტი ajax მოთხოვნისთვის

    Route::get("/fetch_tests", "MainController@Fetch_Tests"); // გვერდებად დაყოფილი ტესტებისთვის, გვერდის ნომერზე დაკლიკების მარშუტი, რათა პირდაპირ გაიხსნას ახალი გვერდი

    Route::get("/search_test", "MainController@Search_Tests"); // ტესტის ძებნის მარშუტი ajax მოთხოვნისთვის

    Route::get("/load_employees", "MainController@Load_Employee"); // თანამსრომელთა ჩატვირთვის მარშუტი ajax მოთხოვნით

    Route::get("/search_employees", "MainController@Search_Employee"); // თანამშრომელთა ძებნის მარშუტი ajax მოთხოვნით

    Route::get("/fetch_employees", "MainController@Fetch_Employees"); // გვერდებად დაყოფილი, თანამშრომელთა გვერდის ნომერზე დაკლიკებისას ახალი გვერდის გახსნის მარშუტი

    Route::get("/filter_employee", "MainController@Filter_Employee"); // თანამშრომელთა ფილტრაციის მარშუტი 

    Route::get("/load_deleteds", "MainController@LoadDeletedQuestions"); // წაშლილი კითხვების ჩატვირთვის მარშუტი

    Route::get("/delete_questions/{id}", "MainController@DeleteQ")->where("id", "[0-9]+"); // კითხვის საბოლოოდ წაშლის მარშუტი

    Route::get("/filter_questions", "MainController@Filter_Questions"); // კითხვების გაფილტვრის მარსუტი ajax მოთხოვნისას

    Route::get("/count_questions/{subject}", "MainController@Count_Questions"); // კითხვების დათვლის მარშუტი თემატიკის მიხედვით ajax მოთხოვნისას. გამოჩნდება კონკრეტულ თემატიკაში რამდენი აქტიური და არააქტიური კითხვაა

    Route::get("/set_all_active", "MainController@SetAllActive"); // არააქტიური კითხვების გააქტიურების მარშუტი ajax მოთხოვნისას

    Route::get("/set_all_inactive", "MainController@SetAllInActive"); // აქტიური კითხვების არააქტიურზე გადაყვანის მარშუტი ajax მოთხოვნისას

    Route::get("/load_test_results", "MainController@LoadTestResults"); // თანამშრომელთა ტესტირების შედგების ცატვირთვა ajax-ით

    Route::get("/fetch_results", "MainController@Fetch_Results"); // შედეგების ჩატვირთვის მარშუტი გვერდის ნომერზე დაკლიკებისას

    Route::post("/edit_multiple", "MainController@EditMultiple"); // ასარჩევი კითხვის დარედაქტირების მარშუტი
?>