<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('agents', function (Blueprint $table) {
            // --- IDENTIFIANTS ---
            $table->uuid('id')->primary(); // ID technique pour l'arborescence et l'historique
            $table->string('matricule', 20)->nullable()->unique(); // Attribué après 1er salaire (Finances)
            $table->string('code_central', 50)->nullable()->unique(); // ID pour la synchronisation DGARH
            
            // --- ÉTAT CIVIL (RECRUTEMENT) ---
            $table->string('nom', 100);
            $table->string('prenom', 100);
            $table->string('nom_complet')->virtualAs("concat(nom, ' ', prenom)");
            $table->enum('sexe', ['M', 'F'])->nullable();
            $table->date('date_naissance')->nullable();
            $table->string('lieu_naissance')->nullable();
            $table->string('nationalite', 100)->default('Congolaise');
            
            // --- DONNÉES D'ORIGINE (ISSUE DE L'ARRÊTÉ/DÉCRET DE RECRUTEMENT) ---
            $table->string('num_recrutement', 100); // Référence de l'acte
            $table->date('date_recrutement');        // Date de signature de l'acte
            $table->string('grade_recrutement', 100);
            $table->string('categorie_recrutement', 10);
            $table->string('echelle_recrutement', 10);
            $table->string('echelon_recrutement', 10);
            $table->string('indice_recrutement', 10);
            $table->string('diplome_recrutement', 255);
            $table->string('etablissement_recrutement', 255)->nullable();

            // --- DONNÉES COMPLÉMENTAIRES (SAISIES EN LOCAL À LA PRISE DE SERVICE) ---
            $table->enum('etat_matrimonial', ['celibataire', 'marie', 'divorce', 'veuf'])->nullable();
            $table->integer('nombre_enfants')->default(0);
            $table->string('nom_conjoint', 255)->nullable();
            $table->string('email_personnel', 191)->nullable();
            $table->string('telephone_personnel', 50)->nullable();
            $table->string('adresse', 500)->nullable();
            $table->string('photo', 255)->nullable();
            
            // --- ÉTAT ET SUIVI CARRIÈRE ACTUELLE ---
            // Note: Ces champs seront mis à jour via les tables d'historique (Affectations/Evolutions)
            $table->enum('statut', ['actif', 'inactif', 'retraite', 'detache', 'disponibilite', 'mission', 'conge'])->default('actif');
            $table->date('date_premiere_prise_service')->nullable();
            $table->date('date_estime_retraite')->nullable();

            // --- SYNCHRONISATION ---
            $table->boolean('est_synchronise')->default(false);
            $table->timestamp('date_synchronisation')->nullable();
            $table->integer('version')->default(1);
            
            // --- TIMESTAMPS ET SÉCURITÉ ---
            $table->timestamps();
            $table->softDeletes();

            // Index pour optimiser les recherches
            $table->index(['nom', 'prenom']);
            $table->index('matricule');
        });
    }

    public function down(): void {
        Schema::dropIfExists('agents');
    }
};
