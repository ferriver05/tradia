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
        Schema::create('exchange_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('requester_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('requested_item_id')->constrained('items')->onDelete('cascade');
            $table->foreignId('offered_item_id')->constrained('items')->onDelete('cascade');
            $table->enum('status', ['pending', 'accepted', 'rejected', 'cancelled', 'completed'])->default('pending');
            $table->timestamp('match_date')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->boolean('confirmed_by_requester')->default(false);
            $table->boolean('confirmed_by_owner')->default(false);
            $table->boolean('cancelled_by_requester')->default(false);
            $table->boolean('cancelled_by_owner')->default(false);
            $table->text('observations')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exchange_requests');
    }
};
