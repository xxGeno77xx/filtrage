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
        Schema::create('entity_details', function (Blueprint $table) {
            $table->id()->nullable();
            $table->unsignedBigInteger("personne_id")->nullable()->constrained(table: "personne");
            $table->dateTime("datelisted")->nullable();
            $table->string("entitytype")->nullable();
            $table->string("gender")->nullable();
            $table->string("reasonlisted")->nullable();
            $table->integer("listReferenceNumber")->nullable();
            $table->string("comments")->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('entity_details');
    }
};
