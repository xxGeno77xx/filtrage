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
        Schema::create('akas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("personne_id")->nullable()->constrained(table: "personne");
            $table->string("category")->nullable();
            $table->string("first")->nullable();
            $table->string("full")->nullable();
            $table->string("last")->nullable();
            $table->string("type")->nullable();
            $table->string("scripttype")->nullable();
            $table->string("middle")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('akas');
    }
};
