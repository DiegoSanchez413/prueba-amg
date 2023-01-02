<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
            $table->unsignedBigInteger('client_id');
            $table->foreign('client_id')->references('id')->on('clients');
            $table->integer('amount');
            $table->timestamps();
        });

        $addPayment = "CREATE PROCEDURE addPayment(IN transaction_id VARCHAR(255), IN client_id INT, IN amount INT) BEGIN INSERT INTO payments (transaction_id, client_id, amount) VALUES (transaction_id, client_id, amount); END";
        DB::unprepared($addPayment);

        $listPayment = "CREATE PROCEDURE listPayment() BEGIN SELECT payments.id, payments.transaction_id, payments.client_id, payments.amount, payments.created_at, payments.updated_at, clients.name, clients.lastname, clients.dob, clients.phone, clients.email, clients.address FROM payments LEFT JOIN clients ON payments.client_id = clients.id; END";
        DB::unprepared($listPayment);

        $updatePayment = "CREATE PROCEDURE updatePayment (IN id INT, IN transaction_id VARCHAR(255), IN client_id INT, IN amount INT) BEGIN UPDATE payments SET transaction_id = transaction_id, client_id = client_id, amount = amount WHERE id = id; END";
        DB::unprepared($updatePayment);

        $removePayment = "CREATE PROCEDURE removePayment (IN id INT) BEGIN DELETE FROM payments WHERE id = id; END";
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
        $deleteAddPayment = "DROP PROCEDURE IF EXISTS addPayment";
        $deletelistPayment = "DROP PROCEDURE IF EXISTS listPayment";
        $deleteupdatePayment = "DROP PROCEDURE IF EXISTS updatePayment";
        $deleteremovePayment = "DROP PROCEDURE IF EXISTS removePayment";

    }
}
