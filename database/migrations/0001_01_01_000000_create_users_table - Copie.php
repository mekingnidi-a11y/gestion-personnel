Schema::create('pointages', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('agent_id')->constrained('agents');
    $table->foreignUuid('direction_id')->constrained('directions');
    
    $table->date('date_pointage');
    $table->time('heure_arrivee')->nullable();
    $table->time('heure_depart')->nullable();
    $table->integer('minutes_travaillees')->default(0); // Durée brute calculée
    
    // Statuts possibles
    $table->enum('statut', ['present', 'absent', 'justifie', 'permission', 'conge'])->default('present');
    $table->text('motif')->nullable(); // Pour les permissions ou justifications
    
    $table->boolean('est_synchronise')->default(false);
    $table->timestamps();
    
    // Un agent ne peut avoir qu'une ligne par date
    $table->unique(['agent_id', 'date_pointage']);
});
