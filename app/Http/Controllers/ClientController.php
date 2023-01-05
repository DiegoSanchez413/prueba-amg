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
        $deletePayments = $request->input('deletePayments');
        $array = array($client['id'], $client['name'], $client['lastname'], $client['dob'], $client['phone'], $client['email'], $client['address'], json_encode($payments), json_encode($deletePayments));
        return DB::select('call updateClient(?, ?, ?, ?, ?, ?, ?, ?, ?)', $array);
    }

    public function delete($id)
    {
        $result = DB::select('call removeClient(?)', [$id]);
        return response()->json($result, 200);
    }
}
