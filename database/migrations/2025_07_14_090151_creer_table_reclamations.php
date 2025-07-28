<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreerTableReclamations extends Migration
{
    public function up()
    {
        Schema::create('reclamations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_citoyen');
            $table->string('titre');
            $table->text('description');
            $table->string('status');
            $table->string('priorite');
            $table->timestamp('resolved_at')->nullable();
            $table->unsignedBigInteger('agent_id')->nullable();
            $table->string('fichier')->nullable();
            $table->integer('satisfaction_citoyen')->nullable();
            $table->timestamps();

            $table->foreign('id_citoyen')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on('utilisateurs')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::dropIfExists('reclamations');
    }
}