<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_assets', function (Blueprint $table) {
            $table->id();
            $table->string('disk', 20)->default('private_media');
            $table->string('path')->nullable();
            $table->string('thumb_path')->nullable();
            $table->string('original_name');
            $table->string('title');
            $table->string('alt')->nullable();
            $table->string('caption')->nullable();
            $table->string('credit')->nullable();
            $table->string('category', 30)->default('general'); // assistance|logo_partenaire|organigramme|general
            $table->string('mime', 60)->nullable();
            $table->unsignedBigInteger('size')->default(0);
            $table->unsignedInteger('width')->nullable();
            $table->unsignedInteger('height')->nullable();
            $table->boolean('is_public')->default(false);
            $table->boolean('consent_confirmed')->default(false);
            $table->date('action_date')->nullable();
            $table->string('linked_request', 40)->nullable();
            $table->string('status', 15)->default('processing'); // processing|ready|failed
            $table->string('incoming_path')->nullable();
            $table->foreignId('uploaded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('archived_at')->nullable();
            $table->timestamps();

            $table->index('category');
            $table->index('status');
        });

        // Où un fichier est utilisé : bloque la suppression et alimente la liste « pages concernées ».
        Schema::create('media_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_asset_id')->constrained()->cascadeOnDelete();
            $table->string('model_type')->nullable();
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('label');
            $table->string('url')->nullable();
            $table->timestamps();

            $table->index(['model_type', 'model_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_usages');
        Schema::dropIfExists('media_assets');
    }
};
