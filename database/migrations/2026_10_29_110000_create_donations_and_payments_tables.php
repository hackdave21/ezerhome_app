<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Commandes : posées dès la phase 1 pour l'architecture (formations/coaching en phase 2).
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->unsignedBigInteger('amount');
            $table->char('currency', 3)->default('XOF');
            $table->string('status', 20)->default('initie');
            $table->timestamps();
        });

        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->unsignedBigInteger('amount'); // francs CFA (XOF)
            $table->char('currency', 3)->default('XOF');
            $table->string('method', 10); // mixx|moov (moyen choisi par le donateur)
            $table->boolean('is_anonymous')->default(false);
            $table->text('donor_name')->nullable();    // chiffré ; jamais pour un don anonyme
            $table->text('donor_contact')->nullable();  // chiffré ; jamais pour un don anonyme
            $table->boolean('recurring')->default(false); // don régulier = rappel mensuel (pas de prélèvement)
            $table->foreignId('payment_id')->nullable();
            $table->string('receipt_no', 30)->nullable(); // reçu PDF : phase 2
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('donation_id')->nullable()->constrained('donations')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->char('currency', 3)->default('XOF');
            $table->string('status', 20)->default('initie');
            $table->string('gateway', 10)->default('manual'); // manual|mixx|moov
            $table->string('external_ref')->nullable();  // référence de transaction (confirmation)
            $table->string('masked_number', 30)->nullable(); // numéro masqué si nécessaire
            $table->foreignId('confirmed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

            $table->index('status');
            $table->index('gateway');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('donations');
        Schema::dropIfExists('orders');
    }
};
