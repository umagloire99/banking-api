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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->unique();
            $table->string('name');
            $table->string('email')->nullable();
            $table->date('dob')->comment('date of birth of the customer');
            $table->string('address')->comment('address of the customer');
            $table->foreignId('user_id')->comment('track the user who register the customer')
                ->constrained()->onDelete('cascade');
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
        Schema::dropIfExists('customers');
    }
};
