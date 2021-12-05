<?php

namespace App\Http\Controllers;

use App\Models\Content;
use App\Models\User;
use App\Services\AWSFileStorageService;
use App\Services\JWTService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Auth0\Login\Contract\Auth0UserRepository;

class CustomerController extends Controller
{
    public function __construct(
        private AWSFileStorageService $AWSFileStorageService
    ) {}

    public function profile(Request $request) {
        return $this->sendData($request->user->toArray());
    }

    public function update(Request $request) {
        try {
            $user = $request->user;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->name = $request->name;
            $user->save();
            return $this->sendOk();
        } catch (\Exception $e) {
            return $this->handleException($e);
        }
    }

    public function home() {
        $banner = Content::where('key', 'banner')->first();
        $running_text = Content::where('key', 'running_text')->first();

        return $this->sendData([
            "banner" => $banner->value,
            "running_text" => $running_text->value,
        ]);
    }

    public function setHome(Request $request) {
        $banner = Content::where('key', 'banner')->first();
        $running_text = Content::where('key', 'running_text')->first();

        if($request->hasFile('photo')) {
            $photo = $request->file("photo");

            $filename = md5($request->file("photo")->getClientOriginalName() . "dddd");

            $photoUrl = $this->AWSFileStorageService->save(file_get_contents($photo), $filename);

            $photoUrl = $this->AWSFileStorageService->getUrl($filename);

            $banner->value = $photoUrl;
            $banner->save();
        } else {
            $running_text->value = $request->running_text;
            $running_text->save();
        }

        return $this->sendOk();
    }
}
