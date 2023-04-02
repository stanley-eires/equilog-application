<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Query\Expression;
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
        Schema::create('transactions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('amount');
            $table->string('transaction_gateway');
            $table->boolean('status')->default(0);
            $table->uuid('invoice_id');
            $table->json('meta')->default(new Expression('(JSON_ARRAY())'));
            $table->timestamps();
        });
        \App\Models\CoursesUsers::seedCourseUsers();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
