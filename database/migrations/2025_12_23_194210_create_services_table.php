<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('services', function (Blueprint $table) {
            $table->uuid('code')->primary();
            $table->string('code_central')->nullable()->unique();
            $table->foreignUuid('code_direction')->constrained('directions', 'code')->onDelete('cascade');
            $table->string('nom');
            $table->text('missions')->nullable();
            $table->boolean('est_synchronise')->default(false);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('services'); }
};
