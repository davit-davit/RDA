<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Users;
use Hash;
use DB;

class UsersSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Users::insert([
                [
                "name" => "დავით",
                "lastname" => "ჭეჭელაშვილი",
                "pid" => 59001110101,
                "phone" => 598134555,
                "email" => "davit.chechelashvili@rda.gov.ge",
                "department" => "პროექტების განვითარების დეპარტამენტი",
                "service" => "სერვისების განვითარების სამსახური",
                "position" => "უფროსი სპეციალისტი",
                "password" => Hash::make("1234"),
                "role" => "hr" //hr, employee, manager (ეს როლები იქნება ძირითადად სისტემაში)
            ],[
                "name" => "ინა",
                "lastname" => "მამრიკიშვილი",
                "pid" => 59001110102,
                "phone" => 555285585,
                "email" => "ina.mamrikishvili@rda.gov.ge",
                "department" => "პროექტების განვითარების დეპარტამენტი",
                "service" => "სერვისების განვითარების სამსახური",
                "position" => "მთავარი სპეციალისტი",
                "password" => Hash::make("1234"),
                "role" => "hr" //hr, employee, manager (ეს როლები იქნება ძირითადად სისტემაში)
            ],[
                "name" => "დავით",
                "lastname" => "გულდედავა",
                "pid" => 59001110103,
                "phone" => 551415533,
                "email" => "daviti.guldedava@rda.gov.ge",
                "department" => "ადამიანური რესურსების მართვის დეპარტამენტი",
                "service" => "ადამიანური რესურსების განვითარების სამსახური",
                "position" => "მთავარი სპეციალისტი",
                "password" => Hash::make("1234"),
                "role" => "hr" //hr, employee, manager (ეს როლები იქნება ძირითადად სისტემაში)
            ],[
                "name" => "ქეთევან",
                "lastname" => "ლაცაბიძე",
                "pid" => 59001110105,
                "phone" => 551415530,
                "email" => "ketevan.latsabidze@rda.gov.ge",
                "department" => "ადამიანური რესურსების მართვის დეპარტამენტი",
                "service" => "ადამიანური რესურსების განვითარების სამსახური",
                "position" => "სამსახურის უფროსი",
                "password" => Hash::make("1234"),
                "role" => "hr" //hr, employee, manager (ეს როლები იქნება ძირითადად სისტემაში)
            ]
        ]);
    }
}