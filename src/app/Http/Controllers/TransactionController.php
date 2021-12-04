<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Services\AWSFileStorageService;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function __construct(
        private AWSFileStorageService $AWSFileStorageService
    ) {}

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

    public function uploadProof(Request $request) {
        $transaction = Transaction::where('id', $request->id)->first();

        if($transaction->user_id == $request->user->id) {
            if($transaction->status == 'waiting_for_payment') {
                $shipment_data = $transaction->shipment_data;
                $shipment_keys = ["nama_rekening", "nomor_rekening", "nominal", "payment_bank"];
                foreach($shipment_keys as $value) {
                    $shipment_data->{$value} = $request->{$value};
                }

                $transaction->shipment_data = $shipment_data;
                
                $photo = $request->file("photo");

                $filename = md5($request->file("photo")->getClientOriginalName() . "sex3d");

                $photoUrl = $this->AWSFileStorageService->save(file_get_contents($photo), $filename);

                $photoUrl = $this->AWSFileStorageService->getUrl($filename);

                $transaction->payment_proof_link = $photoUrl;

                $transaction->status = 'waiting_for_confirmation';
                
                $transaction->save();
            }
            return $this->sendOk();
        } else {
            return $this->handleException(new \Exception("unauthorized for resource"));
        }
    }

    public function confirm(Request $request) {
        $transaction = Transaction::where('id', $request->id)->first();

        $transaction->status = 'on_progress';

        $transaction->save();

        return $this->sendOk();
    }

    public function tracking(Request $request) {
        $transaction = Transaction::where('id', $request->id)->first();

        $transaction->shipment_data->tracking_code = $request->tracking_code;

        $transaction->status = 'on_delivery';

        $transaction->save();

        return $this->sendOk();
    }
}
