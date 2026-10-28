<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('charters', function (Blueprint $table) {
            $table->id();
            $table->string('type', 20); // listening | provider
            $table->string('version', 20);
            $table->longText('body');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->unique(['type', 'version']);
            $table->index(['type', 'published_at']);
        });

        // Preuve de consentement : une ligne par signature, jamais modifiée.
        Schema::create('charter_signatures', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('charter_id')->constrained()->restrictOnDelete();
            $table->timestamp('signed_at');
            $table->string('ip', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'charter_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('charter_signatures');
        Schema::dropIfExists('charters');
    }
};
