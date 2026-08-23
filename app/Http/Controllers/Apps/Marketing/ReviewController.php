<?php

namespace App\Http\Controllers\Apps\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\ReviewDataTable;
use App\Models\ReviewImage; 
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class ReviewController extends Controller
{
    public function index(ReviewDataTable $dataTable){
        return $dataTable->render('pages.apps.review.list');
    }

     public function imageReview(){
        $images = ReviewImage::all();
        return view('pages.apps.review.image_review', compact('images'));
    }

     public function imageReviewadd(Request $request){
        $request->validate([
            'image' => 'required|image'
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_.' . $image->getClientOriginalExtension();
            $imagePath = 'uploads/review/' . $imageName;
            $image->move(public_path('uploads/review'), $imageName);

            // Save to database
            $sliderBanner = new ReviewImage();
            $sliderBanner->image = $imagePath;
            $sliderBanner->save();
        }

    }

    public function deleteReview($id)
    {
        $sliderBanner = ReviewImage::find($id);

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
