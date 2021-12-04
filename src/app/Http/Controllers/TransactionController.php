<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function list(Request $request) {
        return $this->sendData($request->user->transactions->sortBy("created_at"));
    }

    public function listAdmin(Request $request) {
        return $this->sendData(Transaction::orderBy("created_at", "desc")->get());
    }

    public function detail(Request $request) {
        $transaction = Transaction::where('id', $request->id)->first();

        return $this->sendData($transaction);
    }

    public function confirm(Request $request) {
        $transaction = Transaction::where('id', $request->id)->first();

        $transaction->status = 'on_progress';
        
        $transaction->save();

        return $this->sendOk();
    }
}
