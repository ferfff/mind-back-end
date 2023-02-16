<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('english_level', ['A1','A2','B1','B2','C1','C2',])->default('A1');
            $table->string('knowledge', 100)->nullable();
            $table->string('link_cv', 100)->nullable();
            $table->enum('role', ['superadmin', 'admin', 'normal'])->default('normal');
            $table->rememberToken();
            $table->timestamps();
        });
        
        Artisan::call('db:seed', [
            '--class' => 'UsersTableSeeder',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
