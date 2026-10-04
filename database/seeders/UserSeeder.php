<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //User::factory()->count(3)->create();

        DB::table('users')->insert([
            'username' => 'Usuario Teste',
            'email' => '20241ctb0100016@estudantes.ifpr.edu.br',
            'password' => bcrypt('teste123')
        ]);
    }
}