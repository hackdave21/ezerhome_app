<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Un compte peut n'avoir qu'un téléphone, et aucun mot de passe (connexion par code ou lien).
            $table->string('email')->nullable()->change();
            $table->string('password')->nullable()->change();

            $table->string('phone', 20)->nullable()->unique()->after('email');
            $table->timestamp('phone_verified_at')->nullable()->after('email_verified_at');

            $table->string('status', 30)->default('pending_verification')->index();
            $table->timestamp('deactivated_at')->nullable();
            $table->string('deactivation_reason', 30)->nullable();
            $table->timestamp('reactivated_at')->nullable();
            $table->timestamp('last_activity_at')->nullable();
            $table->string('locale', 5)->default('fr');

            $table->string('preferred_contact', 20)->nullable();
            $table->timestamp('password_changed_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique(['phone']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'phone', 'phone_verified_at', 'status', 'deactivated_at', 'deactivation_reason',
                'reactivated_at', 'last_activity_at', 'locale', 'preferred_contact', 'password_changed_at',
            ]);
        });
    }
};
