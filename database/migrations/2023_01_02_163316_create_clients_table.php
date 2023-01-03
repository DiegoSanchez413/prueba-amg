<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('lastname');
            $table->date('dob');
            $table->string('phone');
            $table->string('email');
            $table->string('address');
            $table->timestamps();
        });

        $addClient = "CREATE PROCEDURE addClient(IN name VARCHAR(255), IN lastname VARCHAR(255), IN dob DATE, IN phone VARCHAR(255), IN email VARCHAR(255), IN address VARCHAR(255)) BEGIN INSERT INTO clients (name, lastname, dob, phone, email, address) VALUES (name, lastname, dob, phone, email, address); SELECT id, true AS success FROM clients WHERE id = LAST_INSERT_ID(); END";
        DB::unprepared($addClient);

        $listClient = "CREATE PROCEDURE listClient() BEGIN SELECT clients.id, CONCAT(clients.name, ' ', clients.lastname) AS fullname, clients.dob, clients.phone, clients.email, clients.address, SUM(payments.amount) AS total, (SELECT COUNT(amount) FROM payments) AS payments FROM clients LEFT JOIN payments ON clients.id = payments.client_id GROUP BY clients.id; END";
        DB::unprepared($listClient);

        $updateClient = "CREATE PROCEDURE updateClient (IN id INT, IN name VARCHAR(255), IN lastname VARCHAR(255), IN dob DATE, IN phone VARCHAR(255), IN email VARCHAR(255), IN address VARCHAR(255)) BEGIN UPDATE clients SET name = name, lastname = lastname, dob = dob, phone = phone, email = email, address = address WHERE id = id; END";
        DB::unprepared($updateClient);

        $removeClient = "CREATE PROCEDURE removeClient (IN id INT) BEGIN DELETE FROM clients WHERE id = id; END";
        DB::unprepared($removeClient);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('clients');
    }
}
