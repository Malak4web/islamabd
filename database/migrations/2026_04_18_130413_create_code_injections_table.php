<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('code_injections', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('code');
            $table->enum('location', ['head', 'body_start', 'body_end'])->default('head');
            $table->boolean('is_active')->default(true);
            $table->json('pages')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('code_injections');
    }
};
