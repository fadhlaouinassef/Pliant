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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_utilisateur')->references('id')->on('utilisateurs')->onDelete('cascade');
            $table->foreignId('id_reclamation')->constrained('reclamations')->onDelete('cascade');
            $table->text('message');
            $table->string('type')->default('comment'); // Type de notification: 'comment', 'status', etc.
            $table->boolean('etat')->default(false); // false = non lu, true = lu
            $table->text('data')->nullable(); // Données JSON supplémentaires si nécessaire
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
