<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'ferfff',
            'email' => 'ferchofff@gmail.com',
            'password' => bcrypt('password'),
            'english_level' => 'B2',
            'knowledge' => 'I know everything',
            'link_cv' => 'http://localhost/',
        ]);
    }
}