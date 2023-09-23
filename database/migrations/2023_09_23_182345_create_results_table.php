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
        Schema::create('results', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->tinyInteger('completed')->default(0);
            $table->bigInteger('timer')->nullable();
            $table->bigInteger('total_score');
            $table->unsignedBigInteger('progress_id');
            $table->unsignedBigInteger('student_id');
            $table->unsignedBigInteger('module_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->foreign('module_id')->references('id')->on('modules')->onDelete('cascade');
            $table->foreign('progress_id')->references('id')->on('progress')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->dropSoftDeletes();
        });  
        Schema::dropIfExists('results');
    }
};
