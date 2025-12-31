<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('pointages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('agent_id')->constrained('agents')->onDelete('cascade');
            $table->foreignUuid('direction_id')->constrained('directions');
            
            $table->date('date_pointage');
            $table->time('heure_arrivee')->nullable();
            $table->time('heure_depart')->nullable();
            $table->integer('minutes_travaillees')->default(0); 
            
            $table->enum('statut', ['present', 'absent', 'justifie', 'permission', 'conge'])->default('present');
            $table->text('motif')->nullable();
            
            $table->boolean('est_synchronise')->default(false);
            $table->timestamps();

            // Index pour les rapports et unicité
            $table->unique(['agent_id', 'date_pointage']);
            $table->index('date_pointage');
        });
    }

    public function down(): void {
        Schema::dropIfExists('pointages');
    }
};
