<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

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
            'email' => 'fernando.maldonado@arkusnexus.com',
            'password' => bcrypt('password'),
            'english_level' => 'B2',
            'knowledge' => 'The more I know the less I undestand',
            'link_cv' => 'http://localhost/',
            'role' => 'superadmin',
        ]);
    }
}
