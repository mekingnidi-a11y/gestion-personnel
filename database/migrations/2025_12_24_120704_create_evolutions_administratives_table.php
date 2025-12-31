<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('evolutions_administratives', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('agent_id')->constrained('agents')->onDelete('cascade');
            
            // Situation qui change
            $table->string('grade');
            $table->string('categorie');
            $table->string('echelle')->nullable();
            $table->string('echelon')->nullable();
            $table->string('indice')->nullable();
            $table->string('diplome_actuel')->nullable();
            $table->string('etablissement_diplome')->nullable();
            
            // Acte justifiant le changement (Promotion/Reclassement)
            $table->string('ref_acte_evolution'); 
            $table->date('date_effet');
            $table->boolean('est_actuel')->default(true);
            
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('evolutions_administratives'); }
};
