<?php

declare(strict_types=1);

use App\Enums\SendStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('send_logs', function (Blueprint $table): void {
            $table->uuid('id')->primary();
            $table->uuid('subscriber_id');
            $table->date('week_of');
            $table->string('status')->default(SendStatus::Sending->value);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->foreign('subscriber_id')
                ->references('id')
                ->on('subscribers')
                ->cascadeOnDelete();

            $table->unique(['subscriber_id', 'week_of']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('send_logs');
    }
};
