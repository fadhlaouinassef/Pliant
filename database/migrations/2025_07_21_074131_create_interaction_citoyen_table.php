<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInteractionCitoyenTable extends Migration
{
    public function up()
    {
        Schema::create('interaction_citoyen', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_reclamation')
                  ->constrained('reclamations')
                  ->onDelete('cascade');
            $table->foreignId('id_utilisateur')
                  ->constrained('utilisateurs')
                  ->onDelete('cascade');
            $table->enum('type', ['aime', 'pas_aime']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('interaction_citoyen');
    }
}
