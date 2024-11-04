<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('bank_transactions', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->date('transaction_date'); // Transaction date
            $table->string('bank_from'); // Bank from
            $table->string('bank_to'); // Bank to
            $table->string('sender'); // Sender
            $table->string('receiver'); // Receiver
            $table->string('transaction_method'); // Transaction method (e.g., wire, transfer, etc.)
            $table->decimal('transaction_amount', 15, 2); // Amount with up to 15 digits and 2 decimal places
            $table->text('remarks')->nullable(); // Remarks (optional)
            $table->string('sender_bank_branch')->nullable(); // Sender bank branch (optional)
            $table->string('receiver_bank_branch')->nullable(); // Receiver bank branch (optional)
            $table->string('sender_contact')->nullable(); // Sender contact (optional)
            $table->string('receiver_contact')->nullable(); // Receiver contact (optional)
            $table->foreignId('created_by')->constrained('users');// Foreign key for user who created the transaction
            $table->timestamps(); // Created at & Updated at columns
        });
    }

    public function down()
    {
        Schema::dropIfExists('bank_transactions');
    }
};
