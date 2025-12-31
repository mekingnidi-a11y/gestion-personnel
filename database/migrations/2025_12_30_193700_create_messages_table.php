<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('messages', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->foreignUuid('sender_id')->constrained('users')->onDelete('cascade');
            // receiver_id est NULL si est_diffusion = true
            $table->foreignUuid('receiver_id')->nullable()->constrained('users')->onDelete('cascade');
            
            $table->string('objet');
            $table->text('contenu');
            $table->boolean('est_diffusion')->default(false);
            $table->timestamp('read_at')->nullable(); // Uniquement pour messages privés
            $table->timestamps();
        });

        // Table pivot pour marquer qui a lu une diffusion
        Schema::create('message_user_read', function (Blueprint $table) {
            $table->foreignUuid('message_id')->constrained('messages')->onDelete('cascade');
            $table->foreignUuid('user_id')->constrained('users')->onDelete('cascade');
            $table->timestamp('read_at');
            $table->primary(['message_id', 'user_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('message_user_read');
        Schema::dropIfExists('messages');
    }
};
