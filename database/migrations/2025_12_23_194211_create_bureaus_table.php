<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('bureaux', function (Blueprint $table) {
            $table->uuid('code')->primary();
            $table->string('code_central')->nullable()->unique();
            $table->foreignUuid('code_service')->constrained('services', 'code')->onDelete('cascade');
            $table->string('nom');
            $table->string('localisation')->nullable();
            $table->integer('capacite')->nullable();
            $table->boolean('est_synchronise')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('bureaux'); }
};
