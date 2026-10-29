<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('listening_requests', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique(); // exposé dans les URLs (dossier sensible)
            $table->foreignId('consultant_id')->constrained('users')->cascadeOnDelete();
            $table->text('description'); // chiffré au repos (cast « encrypted »)
            $table->string('urgency', 20)->nullable(); // faible|moyenne|elevee (facultatif)
            $table->string('preferred_channel', 20)->nullable();
            $table->string('status', 30)->default('envoyee');
            $table->foreignId('assigned_therapist_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('assigned_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('assigned_at')->nullable(); // pour la statistique « délai moyen d'affectation »
            $table->timestamp('accepted_at')->nullable();
            $table->string('decline_reason')->nullable(); // motif de déclinaison (non clinique)
            $table->timestamp('closed_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index(['consultant_id', 'status']);
            $table->index(['assigned_therapist_id', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('listening_requests');
    }
};
