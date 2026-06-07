<?php

declare(strict_types=1);

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
        Schema::create('subscribers', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->string('email')->unique();
            $table->string('niche');
            $table->string('platform');
            $table->string('language');
            $table->string('whatsapp_number')->nullable();
            $table->uuid('referrer_id')->nullable();
            $table->uuid('confirm_token')->unique()->nullable();
            $table->uuid('unsubscribe_token')->unique();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('bounce_reason', 100)->nullable();
            $table->timestamps();

            $table->index(['confirmed_at', 'unsubscribed_at']);
        });

        Schema::table('subscribers', function (Blueprint $table): void {
            $table->foreign('referrer_id')
                ->references('id')
                ->on('subscribers')
                ->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscribers');
    }
};
