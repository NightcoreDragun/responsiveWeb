<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostAndMediaTables extends Migration
{
    public function up()
    {
        Schema::create('post', function (Blueprint $table) {
            $table->id();
            $table->text('commentaire');
            $table->dateTime('dateDeCreation');
            $table->dateTime('dateDeModification')->nullable();
            $table->timestamps();
        });

        Schema::create('media', function (Blueprint $table) {
            $table->id();
            $table->dateTime('dateDeCreation');
            $table->string('nomFichierMedia');
            $table->string('typeMedia');
            $table->unsignedBigInteger('post_id');
            $table->timestamps();

            $table->foreign('post_id')->references('id')->on('post')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('media');
        Schema::dropIfExists('post');
    }
}
