<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for($i = 1; $i <= 3; $i++){
            \App\Models\User::factory()->create([
                'name'=>"admin{$i}",
                'email'=>"admin{$i}@admin.com",
                'type'=>'teacher',
                'password'=>Hash::make('admin1234')
            ]);
        }

        for($i = 1; $i <= 3; $i++){
            \App\Models\User::factory()->create([
                'name'=>"student{$i}",
                'email'=>"student{$i}@student.com",
                'type'=>'student',
                'password'=>Hash::make('student1234')
            ]);
        }
    }
}
