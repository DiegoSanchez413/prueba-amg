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
        $payments = $request->input('payments');
        $array = array($client['name'], $client['lastname'], $client['dob'], $client['phone'], $client['email'], $client['address'], json_encode($payments));
        DB::select('call addClient(?, ?, ?, ?, ?, ?, ?)', $array);
        return response()->json(['success' => true], 200);
    }

    public function update(Request $request)
    {
        $client = $request->input('client');
        $payments = $request->input('payments');

        $array = array($client['id'], $client['name'], $client['lastname'], $client['dob'], $client['phone'], $client['email'], $client['address'], json_encode($payments));

        return DB::select('call updateClient(?, ?, ?, ?, ?, ?, ?, ?)', $array);

        // $paymentsCount = DB::select('call getPaymentCountsOfClient(?)', [$client['id']])[0]->quantity;
        // $incomingPayments = count($request->input('payments'));
        // $payments = $request->input('payments');

        // if ($paymentsCount < $incomingPayments) {
        //     $payments = array_slice($payments, $paymentsCount);
        //     foreach ($payments as $key => $value) {
        //         $transaction_id = $value['transaction_id'];
        //         $amount = $value['amount'];
        //         $clientId = $client['id'];
        //         $date = $value['date'];
        //         $array = array($transaction_id, $clientId, $amount, $date);
        //         $query = DB::select('call addPayment(?, ?, ?, ?)', $array);
        //     }
        // } else {
        //     if (count($payments) > 0) {
        //         foreach ($payments as $key => $value) {
        //             $id = $value['id'];
        //             $transaction_id = $value['transaction_id'];
        //             $clientId = $client['id'];
        //             $amount = $value['amount'];
        //             $date = $value['date'];
        //             $array = array($id, $transaction_id, $clientId, $amount, $date);
        //             $query = DB::select('call updatePayment(?, ?, ?, ?, ?)', $array);
        //         }
        //     }
        // }
        // return response()->json($result, 200);
    }

    public function delete($id)
    {
        $result = DB::select('call removeClient(?)', [$id]);
        return response()->json($result, 200);
    }
}
