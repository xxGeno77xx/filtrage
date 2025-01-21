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
        Schema::create('conflicts', function (Blueprint $table) {
            $table->id()->nullable();
            $table->unsignedBigInteger("personne_id")->nullable()->constrained(table: "personne");
            $table->boolean("addressconflict")->nullable();
            $table->boolean("citizenshipconflict")->nullable();
            $table->boolean("countryconflict")->nullable();
            $table->boolean("dobconflict")->nullable();
            $table->boolean("entitytypeconflict")->nullable();
            $table->boolean("genderconflict")->nullable();
            $table->boolean("idconflict")->nullable();
            $table->boolean("phoneconflict")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('conflicts');
    }
};
