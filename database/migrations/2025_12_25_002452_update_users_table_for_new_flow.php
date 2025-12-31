<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rend le mot de passe et l'email nullables sans supprimer les colonnes
            $table->string('password')->nullable()->change();
            $table->string('email')->nullable()->change();

            // Ajoute les nouvelles colonnes nécessaires au flux 2025
            if (!Schema::hasColumn('users', 'est_valide')) {
                $table->boolean('est_valide')->default(false)->after('role');
            }
            if (!Schema::hasColumn('users', 'doit_changer_password')) {
                $table->boolean('doit_changer_password')->default(true)->after('est_valide');
            }
            if (!Schema::hasColumn('users', 'a_demande_reset')) {
                $table->boolean('a_demande_reset')->default(false)->after('doit_changer_password');
            }
            if (!Schema::hasColumn('users', 'code_direction')) {
                $table->string('code_direction')->nullable()->after('role');
            }
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('password')->nullable(false)->change();
            $table->string('email')->nullable(false)->change();
            $table->dropColumn(['est_valide', 'doit_changer_password', 'a_demande_reset', 'code_direction']);
        });
    }
};
