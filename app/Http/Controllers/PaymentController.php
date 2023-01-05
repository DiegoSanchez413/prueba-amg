<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $array = $request->all();
        foreach ($array as $key => $value) {
            $transaction_id = $value['transaction_id'];
            $amount = $value['amount'];
            $clientId = $value['clientId'];
            $date = $value['date'];
            $array = array($transaction_id, $clientId, $amount, $date);
            $result = DB::select('call addPayment(?, ?, ?, ?)', $array);
        }
        return response()->json($result, 200);
    }

    public function getClientPayments($id)
    {
        $result = DB::select('call getPaymentsById(?)', [$id]);
        return response()->json($result, 200);
    }

    public function deletePayment($id){
        $result = DB::select('call deletePayment(?)', [$id]);
        return response()->json($result, 200);
    }

    
}
