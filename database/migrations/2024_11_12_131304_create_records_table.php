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
        Schema::create('records', function (Blueprint $table) {
            $table->id()->nullable();
            $table->unsignedBigInteger("personne_id")->nullable()->constrained(table: "personne");
            $table->string("client_ref")->nullable();
            $table->integer("record")->nullable();
            $table->integer("result_id")->nullable();
            $table->integer("run_id")->nullable();
            $table->boolean("lockedAlert")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('records');
    }
};
