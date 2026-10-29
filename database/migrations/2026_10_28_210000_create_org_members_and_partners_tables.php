<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Organigramme : un membre n'est pas forcément un utilisateur (user_id facultatif).
        Schema::create('org_members', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('position');
            $table->string('committee', 30)->default('autre'); // bureau_executif|comite_assistance|comite_validation|autre
            $table->foreignId('media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->foreignId('parent_id')->nullable()->constrained('org_members')->nullOnDelete();
            $table->text('bio')->nullable();
            $table->string('public_contact')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->boolean('is_visible')->default(true);
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['committee', 'sort_order']);
        });

        // Partenaires : publication uniquement avec accord écrit (approved_at obligatoire pour is_active).
        Schema::create('partners', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('media_id')->nullable()->constrained('media_assets')->nullOnDelete();
            $table->string('url')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->boolean('is_active')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['is_active', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('partners');
        Schema::dropIfExists('org_members');
    }
};
