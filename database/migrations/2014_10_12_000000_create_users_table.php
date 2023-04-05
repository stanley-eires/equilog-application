<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Query\Expression;

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
            $table->uuid('id')->primary();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->string('phone')->nullable();
            $table->string('nin')->nullable();
            $table->string('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('marital_status')->nullable();
            $table->string('address')->nullable();
            $table->string('profile_picture')->nullable();
            $table->json('next_of_kin');
            $table->string('login_at')->nullable();
            $table->json('work_experience');
            $table->json('education');
            $table->json('medicals');
            $table->json('roles');
            $table->boolean('status')->default(1);
            $table->longText('summary')->nullable();
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();
        });

        \App\Models\User::seedUsers();
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
