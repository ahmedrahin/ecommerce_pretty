<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;
use Illuminate\Http\File;
class UserController extends Controller
{
    /**
     * Disply all user list
     * @return User List Array
     */
    public function index()
    {
        $data = [];
        $data['lists'] = User::select(
            'users.id',
            'users.avatar',
            'users.name',
            'users.email',
            'users.ota',
            'users.phone',
            'users.active as status',
            'users.country as country_id',
            'users.currency as currency_id',
            'co.name as country',
            'cu.id as currency',
            'cu.currency as currency'
        )->join('countries as co', 'co.id', '=', 'users.country')
            ->join('countries as cu', 'cu.id', '=', 'users.currency')
            ->get();
        $data['country'] = Country::orderBy('name')->get();
        return view('pages/user/lists', $data);
    }

    public function update(Request $request, $id)
    {
        // dd($request);
        // $user = User::find($id);

        User::where('id', $id)
       ->update([
           'name' => $request->user_name,
           'email' => $request->email,
           'ota' => $request->ota,
           'country' => $request->country,
           'phone' => $request->phone,
           'currency' => $request->currency,
           'active' => $request->status,
        ]);

        $request->session()->flash('success', 'Profile updated successfully!');
        // $user->save();
        return back();
    }

    public function activeInActive($id, $status)
    {  
        if($status == 1){
            $sts = 0;
        }
        else{
            $sts = 1;
        }
        User::where('id', $id)
       ->update([
           'active' => $sts,
        ]);
        $request->session()->flash('success', 'Profile updated successfully!');
        return back();
    }
    
    public function viewProfile()
    {
        $data = [];
        $data['country'] = Country::get();
        //return Auth::user();
        return view('auth/profile', $data);
    }
    
    public function settingViewProfile(Request $request)
    {
        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->email = $request->email;
        $user->ota = $request->ota_name;
        $user->country = $request->country;
        $user->phone = $request->phone;
        $user->currency = $request->currency;
        /* $FIel upload start*/
        //return $originalFile = $request->file('avatar');
        if ($request->hasFile('avatar')) {
            if (Storage::exists($request->avatar_remove)) {
                Storage::delete($request->avatar_remove);
            }

            $file = $request->file('avatar');
            $image = Image::make($file);
            $image->resize(200, 200, function ($constraint) {
                $constraint->aspectRatio();
            });
            
            $thumbnail_image_name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME) . '.' . $file->getClientOriginalExtension();
            $image->save(public_path('uploads/' . $thumbnail_image_name));
            $saved_image_uri = $image->dirname . '/' . $image->basename;

            //Now use laravel filesystem.
            $uploaded_thumbnail_image = Storage::putFileAs('public/avatars', new File($saved_image_uri), $thumbnail_image_name);

            //Now delete temporary intervention image as we have moved it to Storage folder with Laravel filesystem.
            $image->destroy();
            unlink($saved_image_uri);
            $user->avatar = 'avatars/' . $thumbnail_image_name;
        }
        $request->session()->flash('success', 'Profile updated successfully!');
        $user->save();
        return back();
    }
}