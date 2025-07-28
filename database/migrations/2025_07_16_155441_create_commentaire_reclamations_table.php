<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommentaireReclamationsTable extends Migration
{
    public function up()
    {
        Schema::create('commentaire_reclamations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reclamation')->constrained('reclamations')->onDelete('cascade');
            $table->foreignId('id_ecrivain')->constrained('utilisateurs')->onDelete('cascade');
            $table->text('commentaire');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('commentaire_reclamations');
    }
}