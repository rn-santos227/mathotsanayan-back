<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('grades', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('evaluation');
            $table->tinyInteger('skipped');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('result_id');
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('question_id');
            $table->unsignedBigInteger('solution_id');
            $table->unsignedBigInteger('answer_id');
            $table->unsignedBigInteger('correct_id');
            $table->timestamps();
            $table->softDeletes();
            
            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('result_id')->references('id')->on('results')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
            $table->foreign('solution_id')->references('id')->on('solutions')->onDelete('cascade');
            $table->foreign('answer_id')->references('id')->on('answers')->onDelete('cascade');
            $table->foreign('correct_id')->references('id')->on('corrects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('grades', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });  
        Schema::dropIfExists('grades');
    }
};
