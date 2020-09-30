<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\User;
use App\Models\Asset;
use App\Models\Employee;
use App\Models\Department;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
       User::create([
        'name' => 'Jorem Belen',
        'email' => 'jorembelen@gmail.com',
        'type' => 'admin',
        'password' => Hash::make('password'),
       ]);

    //    Asset::create([
    //     'type' => 'Desktop',
    //     'ritcco' => '30-00164',
    //     'description' => 'HP 500B MT',
    //     'serial_number' => 'CZC110C34T',
    //     'mobile_number' => '050123456',
    //     'asset_number' => 'RCLCD-00164',
    //     'image' => '00164.jpg',
    //     'purchased_date' => '2020-07-29',
    //     'remarks' => 'test',
    // ]);

    // Asset::create([
    //     'type' => 'Laptop',
    //     'ritcco' => '30-00165',
    //     'description' => 'DELL INSPIRON',
    //     'serial_number' => 'CZC110C2TK',
    //     'mobile_number' => '050123457',
    //     'asset_number' => 'RCLCD-00165',
    //     'image' => '00165.jpg',
    //     'purchased_date' => '2020-09-09',
    //     'remarks' => 'test',
    // ]);

    Employee::create([
        'name' => 'Peter Parker',
        'department' => 'HR',
        'position' => 'HR Manager',
        'checkIn' => '07:40:00',
        'checkOut' => '17:20:00',
    ]);

    Employee::create([
        'name' => 'Allen Iverson',
        'department' => 'Admin',
        'position' => 'Clerk',
        'checkIn' => '07:40:00',
        'checkOut' => '17:20:00',
    ]);

    Department::create([
        'dep_name' => 'HR',
    ]);

    Department::create([
        'dep_name' => 'Admin',
    ]);


    }
}
