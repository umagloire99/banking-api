<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id')->unique()->comment('Unique transaction identifier');
            $table->float('amount')->comment('Transaction amount');
            $table->float('fee')->default(0.0)->comment('Transaction fee');
            $table->enum('type', ['fund_transfer', 'cash_out', 'cash_in'])
                ->comment('Type of transaction done');
            $table->enum('status', ['pending', 'cancelled', 'complete'])->default('pending');
            $table->longText('reason')->comment('reason of the transaction');

            $table->foreignId('source_id')->comment('Initiator of the transaction')
                ->constrained()->onDelete('cascade')->on('bank_accounts');
            $table->float('source_balance')->comment('Current initiator balance');

            $table->foreignId('destination_id')->comment('Benefactor of the transaction')
                ->constrained()->onDelete('cascade')->on('bank_accounts');
            $table->float('destination_balance')->comment('Current benefactor balance');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};
