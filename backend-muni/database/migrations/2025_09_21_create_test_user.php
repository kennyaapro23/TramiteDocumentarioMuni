<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    public function up()
    {
        // Crear usuario de prueba si no existe
        if (!User::where('email', 'admin@test.com')->exists()) {
            User::create([
                'name' => 'Admin Test',
                'email' => 'admin@test.com',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
            ]);
        }
    }

    public function down()
    {
        User::where('email', 'admin@test.com')->delete();
    }
};