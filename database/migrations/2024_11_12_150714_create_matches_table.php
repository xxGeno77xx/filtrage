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
        Schema::create('matches', function (Blueprint $table) {
            $table->id();
            $table->integer("id_from_api");
            $table->unsignedBigInteger("personne_id")->nullable()->constrained(table: "personne");
            $table->integer("acceptlistid")->nullable();
            $table->boolean("addedtoacceptlist")->nullable();
            $table->string("addressname")->nullable();
            $table->boolean("autofalsepositive")->nullable();
            $table->boolean("bestaddressispartial")->nullable();
            $table->string("bestcountry")->nullable();
            $table->integer("bestcountryscore")->nullable();
            $table->string("bestcountrytype")->nullable();
            $table->boolean("bestdobispartial")->nullable();
            $table->string("bestname")->nullable();
            $table->integer("bestnamescore")->nullable();
            $table->integer("checksum")->nullable();
            $table->string("entityname")->nullable();
            $table->integer("entityscore")->nullable();
            $table->string("entityuniqueid")->nullable();
            $table->boolean("falsepositive")->nullable();
            $table->boolean("gatewayofacscreeningindicatormatch")->nullable();
            $table->boolean("matchrealert")->nullable();
            $table->integer("previousresultid")->nullable();
            $table->string("reasonlisted")->nullable();
            $table->dateTime("resultdate")->nullable();
            $table->boolean("secondaryofacscreeningindicatormatch")->nullable();
            $table->boolean("truematch")->nullable();
            $table->dateTime("datemodified")->nullable();
            $table->string("status")->nullable();



            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
