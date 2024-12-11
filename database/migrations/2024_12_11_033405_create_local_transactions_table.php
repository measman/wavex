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
        Schema::create('local_transactions', function (Blueprint $table) {
            $table->id();
            $table->integer('cur_id');
            $table->decimal('amount', 15, 2);
            $table->enum('type', ['Deposit', 'Withdraw']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('local_transactions');
    }
};
