<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /*
      # Create time capsules table

      1. New Tables
        - `time_capsules`
          - `id` (primary key)
          - `user_id` (foreign key to users)
          - `title` (capsule title)
          - `encrypted_content` (encrypted message content)
          - `content_type` (text, photo, mixed)
          - `unlock_date` (when capsule can be opened)
          - `is_unlocked` (tracking unlock status)
          - `opened_at` (when user first opened it)
          - `reminder_sent` (email reminder tracking)
          - `created_at` and `updated_at` timestamps

      2. Security
        - Foreign key constraint to users table
        - Index on unlock_date for efficient queries
        - Index on user_id for user capsules lookup

      3. Features
        - Support for different content types
        - Reminder system integration
        - Unlock tracking for analytics
    */

    public function up(): void
    {
        Schema::create('time_capsules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->longText('encrypted_content');
            $table->enum('content_type', ['text', 'photo', 'mixed'])->default('text');
            $table->timestamp('unlock_date');
            $table->boolean('is_unlocked')->default(false);
            $table->timestamp('opened_at')->nullable();
            $table->boolean('reminder_sent')->default(false);
            $table->json('attachments')->nullable();
            $table->timestamps();

            $table->index(['unlock_date']);
            $table->index(['user_id', 'unlock_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('time_capsules');
    }
};
