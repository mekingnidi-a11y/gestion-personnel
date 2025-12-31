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
        // TABLE UTILISATEURS (Flux METP 2025)
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('code')->primary(); // Identifiant unique UUID
            $table->string('username')->unique(); // Identifiant de connexion
            
            // Email rendu nullable pour l'inscription optionnelle
            $table->string('email')->nullable()->unique(); 
            $table->timestamp('email_verified_at')->nullable();
            
            // Password nullable pour permettre l'inscription sans mot de passe
            $table->string('password')->nullable(); 
            
            // Hiérarchie et Rôles
            $table->enum('role', ['admin_rh', 'admin_direction', 'chef_service', 'agent'])->default('agent');
            $table->string('code_direction')->nullable()->index(); // Liaison avec la table directions
            
            // Gestion des Accès et Flux de sécurité
            $table->boolean('est_valide')->default(false); // Compte activé par l'admin
            $table->boolean('doit_changer_password')->default(true); // Force la définition au premier login
            $table->boolean('a_demande_reset')->default(false); // Flag pour l'oubli de mot de passe
            
            $table->boolean('est_synchronise')->default(false);
            $table->rememberToken();
            $table->timestamps();
        });

        // TOKENS DE RÉINITIALISATION (Standard Laravel)
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // GESTION DES SESSIONS
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignUuid('user_id')->nullable()->index(); // Liaison avec l'UUID 'code' de users
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
