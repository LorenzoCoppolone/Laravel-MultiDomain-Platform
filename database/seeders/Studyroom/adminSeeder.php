<?php

namespace Database\Seeders\Studyroom;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Studyroom\Amministratore;
use Illuminate\Support\Facades\Hash;

// ATTENZIONE: La 'A' deve essere maiuscola
class AdminSeeder extends Seeder 
{
    use WithoutModelEvents;
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Amministratore::create([
            'nome'     => 'admin',
            'email'    => 'admin.studyroom@univaq.it',
            'password' => Hash::make('GOatMaurizioDB30L!'),
        ]);
    }
}