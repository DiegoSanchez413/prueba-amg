<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function list()
    {
        $result = DB::select('call listClient()');
        return response()->json($result, 200);
    }
    public function store(Request $request)
    {
        $client = $request->input('client');
        $array = array($client['name'], $client['lastname'], $client['dob'], $client['phone'], $client['email'], $client['address']);
        $result = DB::select('call addClient(?, ?, ?, ?, ?, ?)', $array);
        $status = $result[0]->success;

        if ($status) {
            $payments = $request->input('payments');
            if (count($payments) > 0) {
                foreach ($payments as $key => $value) {
                    $transaction_id = $value['transaction_id'];
                    $amount = $value['amount'];
                    $clientId = $result[0]->id;
                    $date = $value['date'];
                    $array = array($transaction_id, $clientId, $amount, $date);
                    $query = DB::select('call addPayment(?, ?, ?, ?)', $array);
                }
            }
            return response()->json($result, 200);
        } else {
            return response()->json($result, 400);
        }
    }

    public function update(Request $request)
    {
        $client = $request->input('client');
        $array = array($client['id'], $client['name'], $client['lastname'], $client['dob'], $client['phone'], $client['email'], $client['address']);
        $result = DB::select('call updateClient(?, ?, ?, ?, ?, ?, ?)', $array);
        return response()->json($result, 200);
    }
}
