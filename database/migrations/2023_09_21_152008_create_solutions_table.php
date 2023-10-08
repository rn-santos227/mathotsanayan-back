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
        Schema::create('solutions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->longText('solution');
            $table->string('file')->nullable();
            $table->unsignedBigInteger('module_id');
            $table->unsignedBigInteger('subject_id');
            $table->unsignedBigInteger('question_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
            $table->foreign('question_id')->references('id')->on('questions')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('solutions', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });  
        Schema::dropIfExists('solutions');
    }
};
