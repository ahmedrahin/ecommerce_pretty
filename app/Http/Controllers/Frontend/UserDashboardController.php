<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class UserDashboardController extends Controller
{
    public function dashboard(){
        $user = User::findOrFail(Auth::id());
        $orders = $user->orders()->take(3)->latest()->where('order_date', '>=', now()->subDays(7))->get();
        return view('frontend.pages.user.dashboard', compact('user', 'orders'));
    }

    public function orders(){
        $user = User::findOrFail(Auth::id());
        $orders = $user->orders()->orderBy('id', 'desc')->paginate(5);
        
        return view('frontend.pages.user.order', compact('user', 'orders'));
    }

    public function invoice($user_id, $order_id){
        $user = User::findOrFail(Auth::id());
        $order = Order::whereNotNull('user_id')->where('user_id', $user_id)->where('order_id', $order_id)->where('order_source', 'website')->first();
        if(!$user || !$order){
            return redirect()->back()->with('error', 'Order not found');
        }
        return view('frontend.pages.user.invoice', compact('order', 'user'));
    }

    public function editProfile(){
        $user = User::findOrFail(Auth::id());
        return view('frontend.pages.user.edit-profile', compact('user'));
    }

    public function updatePassword(){
        $user = User::findOrFail(Auth::id());
        return view('frontend.pages.user.update-password', compact('user'));
    }

    public function wishlist(){
        $user = User::findOrFail(Auth::id());
        return view('frontend.pages.user.my-wishlist', compact('user'));
    }

    public function uploadAvatar(Request $request)
    {
        $request->validate([
            'profilePhoto' => 'required|image'
        ]);

        $user = auth()->user();

        if ($user->avatar) {
            Storage::disk('real_public')->delete($user->avatar);
        }

        // Save new image
        $path = $request->file('profilePhoto')->store('uploads/user', 'real_public');

        $user->update(['avatar' => $path]);

        return response()->json([
            'status' => 'success',
            'html' => view('frontend.pages.user.profile-img', compact('user'))->render(),
        ]);
    }

    public function removeAvatar()
    {
        $user = auth()->user();

        if ($user->avatar) {
            Storage::disk('real_public')->delete($user->avatar);
            $user->update(['avatar' => null]);
        }

        return response()->json([
            'status' => 'success',
            'html' => view('frontend.pages.user.profile-img', compact('user'))->render(),
        ]);
    }

}
