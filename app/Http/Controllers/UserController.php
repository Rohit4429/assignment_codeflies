<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{
    public function store(Request $request){
        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|numeric',
            // 'profile_image' => 'required',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $store = new User();
        $store->name = $request->name;
        $store->mobile = $request->mobile;
        $store->email = $request->email;
        $store->password = $request->password;

        if($request->file('profile_image')){

            $profileImage = $request->file('profile_image');
            $resizedImage = Image::make($profileImage)->resize(400, 400);
            $image = Carbon::now()->format('Ymdhsu').'.'.$profileImage->getClientOriginalExtension();
            $resizedImage->save(public_path('images/').$image);
            $store->profile_img =$image;
        }
        $store->save();
        Auth::login($store);

        return redirect('/home');
    }
}
