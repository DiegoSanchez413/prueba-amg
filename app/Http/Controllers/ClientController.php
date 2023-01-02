<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function list(){
        $result = DB::select('call listClient()');
        return response()->json($result, 200);
    }
    public function store(Request $request)
    {
        $name = $request->input('name');
        $lastname = $request->input('lastname');
        $dob = $request->input('dob');
        $phone = $request->input('phone');
        $email = $request->input('email');
        $address = $request->input('address');
        $array = array($name, $lastname, $dob, $phone, $email, $address);
        $result = DB::select('call addClient(?, ?, ?, ?, ?, ?)', $array);
        return response()->json($result);
    }
}
