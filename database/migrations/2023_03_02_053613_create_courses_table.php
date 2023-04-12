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
        Schema::create('courses', function (Blueprint $table) {
            $table->uuid('id');
            $table->string('name');
            $table->string('slug');
            $table->longText('summary');
            $table->longText('description')->nullable();
            $table->boolean('status')->default(1);
            $table->string('program');
            $table->string('cost');
            $table->string('discounted_cost')->nullable();
            $table->string('duration');
            $table->json('learning_methods');
            $table->string('banner')->nullable();
            $table->string('date_of_commencement')->nullable();
            $table->uuid('user_id')->nullable();
            $table->timestamps();
            $table->primary('id');
            $table->softDeletes();
        });

        \App\Models\Course::seedCourses();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('courses');
    }
};
