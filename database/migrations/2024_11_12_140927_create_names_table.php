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
        Schema::create('names', function (Blueprint $table) {
            $table->id()->nullable();
            $table->unsignedBigInteger("personne_id")->nullable()->constrained(table: "personne");
            $table->string("first")->nullable();
            $table->string("full")->nullable();
            $table->string("generation")->nullable();
            $table->string("last")->nullable();
            $table->string("middle")->nullable();
            $table->string("title")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('names');
    }
};
