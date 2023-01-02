<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('transaction_id');
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->integer('amount');
            $table->date('date');
            $table->timestamps();
        });

        $addPayment="CREATE PROCEDURE addPayment(IN transactionId VARCHAR(255), IN clientId INT, IN amount INT, IN date DATE) BEGIN INSERT INTO payments (transaction_id, client_id, amount, date) VALUES (transactionId, clientId, amount, date); END";
        DB::unprepared($addPayment);
        $removePayment = "CREATE PROCEDURE removePayment(IN id INT) BEGIN DELETE FROM payments WHERE id = id; END";
        DB::unprepared($removePayment);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
