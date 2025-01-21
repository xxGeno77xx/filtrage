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
        Schema::create('record_details', function (Blueprint $table) {
            $table->id()->nullable();
            $table->unsignedBigInteger("personne_id")->nullable()->constrained(table: "personne");
            $table->integer("acceptlistid")->nullable();
            $table->string("accountamount")->nullable();
            $table->string("accountdate")->nullable();
            $table->string("accountgroupid")->nullable();
            $table->string("accountotherdata")->nullable();
            $table->string("accountproviderid")->nullable();
            $table->string("accountmemberid")->nullable();
            $table->string("accounttype")->nullable();
            $table->boolean("addedtoacceptlist")->nullable();
            $table->string("predefinedsearch")->nullable();
            $table->string("pdsversion")->nullable();
            $table->string("dppa")->nullable();
            $table->string("efttype")->nullable();
            $table->string("entitytype")->nullable();
            $table->string("gender")->nullable();
            $table->integer("glb")->nullable();
            $table->dateTime("lastupdateddate")->nullable();
            $table->dateTime("searchdate")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('record_details');
    }
};
