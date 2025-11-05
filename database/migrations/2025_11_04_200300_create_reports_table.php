<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('type_id')->constrained('incident_types')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('crew_id')->nullable()->constrained('crews')->nullOnDelete();
            $table->string('zone')->nullable();
            $table->string('status')->index();
            $table->string('location_text')->nullable();
            $table->string('photo_url')->nullable();
            $table->text('description')->nullable();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('resolved_at')->nullable();
            $table->timestamps();
            $table->index(['type_id','zone']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reports');
    }
};