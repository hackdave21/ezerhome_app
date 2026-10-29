<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assistance_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // usage interne (URLs du back-office)
            $table->string('tracking_no', 20)->unique(); // ASS-AAAA-NNNNN, communiqué au demandeur
            // Données personnelles chiffrées au repos.
            $table->text('full_name');
            $table->text('phone');
            $table->char('phone_hash', 64)->index(); // empreinte pour le suivi et la détection de doublons
            $table->text('email')->nullable();
            $table->string('category', 30);
            $table->text('description');
            $table->text('notes')->nullable(); // notes internes du comité
            $table->timestamp('consent_at');
            $table->string('status', 20)->default('recue');
            $table->timestamps();

            $table->index('status');
            $table->index('category');
        });

        // Historique des changements de statut et notes (traçabilité).
        Schema::create('assistance_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assistance_request_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20)->nullable();
            $table->text('note')->nullable(); // chiffré (peut contenir des éléments sensibles)
            $table->foreignId('actor_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('created_at')->nullable();

            $table->index('assistance_request_id');
        });

        // Décaissements : trace simple, sans logique bancaire.
        Schema::create('assistance_disbursements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assistance_request_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount'); // en francs CFA (XOF)
            $table->date('disbursed_on');
            $table->foreignId('responsible_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('label')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assistance_disbursements');
        Schema::dropIfExists('assistance_events');
        Schema::dropIfExists('assistance_requests');
    }
};
