<?php

use Domain\User\Models\User;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class);
            $table->float('amount');
            $table->string('status');
            $table->string('reference_id')->unique()->nullable();
            $table->string('gateway');
            $table->json('metadata')->nullable();
            $table->json('gateway_response')->nullable();
            $table->timestamps();

            $table->index('user_id');
            $table->index('status');
            $table->index('gateway');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
