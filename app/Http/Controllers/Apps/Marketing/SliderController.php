<?php

namespace App\Http\Controllers\Apps\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HomeSlider;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SliderController extends Controller
{
    public function index(){
        $images = HomeSlider::all();
        return view('pages.apps.slider.slider', compact('images'));
    }

    public function store(Request $request){
        $request->validate([
            'image' => 'required|image'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_.' . $image->getClientOriginalExtension();
            $imagePath = 'uploads/slider/' . $imageName;
            $image->move(public_path('uploads/slider'), $imageName);

            // Save to database
            $sliderBanner = new HomeSlider();
            $sliderBanner->image = $imagePath;
            $sliderBanner->save();
        }

    }

    public function destroy($id)
    {
        $sliderBanner = HomeSlider::find($id);

        if (!$sliderBanner) {
            return response()->json(['success' => false, 'message' => 'Image not found'], 404);
        }

        // Delete image file from storage
        $filePath = public_path($sliderBanner->image);
        if (File::exists($filePath)) {
            File::delete($filePath);
        }

        // Delete image record from database
        $sliderBanner->delete();

        return response()->json(['success' => true, 'message' => 'Image deleted successfully']);
    }
}
