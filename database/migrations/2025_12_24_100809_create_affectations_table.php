<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('affectations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            // Lien avec l'UUID de l'agent
            $table->foreignUuid('agent_id')->constrained('agents')->onDelete('cascade');
            
            // Structure (Direction > Service > Bureau)
            $table->foreignUuid('code_direction')->constrained('directions', 'code');
            $table->foreignUuid('code_service')->nullable()->constrained('services', 'code');
            $table->foreignUuid('code_bureau')->nullable()->constrained('bureaux', 'code');
            
            // Détails du mouvement
            $table->string('fonction')->nullable(); // Ex: Chef de Bureau
            $table->string('ref_acte'); // Note de service ou Décret d'affectation
            $table->date('date_debut');
            $table->date('date_fin')->nullable();
            $table->boolean('est_actuelle')->default(true);
            
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('affectations'); }
};
