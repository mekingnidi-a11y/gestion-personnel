<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('directions', function (Blueprint $table) {
            $table->uuid('code')->primary(); // ID unique local
            $table->string('code_central')->nullable()->unique(); // Rempli après synchro
            $table->string('nom');
            $table->enum('type', ['generale', 'centrale', 'departementale', 'rattache_cabinet']);
            
            // Relation auto-référencée : une direction peut avoir une direction parente
            $table->foreignUuid('code_direction_parent')->nullable()
                  ->constrained('directions', 'code')
                  ->onDelete('set null');

            $table->text('missions')->nullable();
            $table->string('arret_creation')->nullable();
            $table->string('contact_email')->nullable();
            $table->string('contact_telephone')->nullable();

            // Champs de synchronisation
            $table->boolean('est_synchronise')->default(false);
            $table->dateTime('date_synchronisation')->nullable();
            $table->integer('version')->default(1);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('directions');
    }
};
