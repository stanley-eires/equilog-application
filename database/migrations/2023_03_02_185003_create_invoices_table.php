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
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->string('invoice_ref');
            $table->json('items')->default(new Expression('(JSON_ARRAY())'));
            $table->string('amount')->nullable();
            $table->string('payment_status')->nullable();
            $table->string('date_paid')->nullable();
            $table->boolean('status')->default(0);
            $table->string('date_approved')->nullable();
            $table->integer('handler')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
};
