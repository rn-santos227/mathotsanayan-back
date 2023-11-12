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
        Schema::create('modules', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('step');
            $table->decimal('passing', 11, 2);
            $table->tinyInteger('active')->default(0);
            $table->unsignedBigInteger('subject_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modules', function (Blueprint $table) {
            $table->dropSoftDeletes();
        }); 
        Schema::dropIfExists('modules');
    }
};
