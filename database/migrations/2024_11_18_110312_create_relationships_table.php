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
        Schema::create('relationships', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("personne_id")->nullable()->constrained(table: "personne");
            $table->string("group")->nullable();
            $table->string("type")->nullable();
            $table->integer("entityId")->nullable();
            $table->dateTime("datemodified")->nullable();
            $table->string("entityname")->nullable();
            $table->double("ownershippercentage")->nullable();
            $table->string("segments")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('relationships');
    }
};
