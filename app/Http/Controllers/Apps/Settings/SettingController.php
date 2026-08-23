<?php

namespace App\Http\Controllers\Apps\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Setting;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\DB;

class SettingController extends Controller
{
    public function manage(){
        return view('pages.apps.setting.genarel');
    }

    public function website_setting(){
        $setting = WebsiteSetting::first();
        return view('pages.apps.setting.website', compact('setting'));
    }

  public function homepagesContent(Request $request)
    {
        $request->validate([
            'new_arrivale_image' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp',
            'marquee_text' => 'nullable|string',
        ]);

        $setting = WebsiteSetting::first() ?: new WebsiteSetting();

        // Handle new arrival image
        if ($request->hasFile('new_arrivale_image')) {
            $image = $request->file('new_arrivale_image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $destination = public_path('uploads/');
            $image->move($destination, $imageName);

            // Delete old image if exists
            if ($setting->new_arrivale_image && file_exists(public_path($setting->new_arrivale_image))) {
                unlink(public_path($setting->new_arrivale_image));
            }

            $setting->new_arrivale_image = 'uploads/' . $imageName;
        } else {
            if ($setting->new_arrivale_image && file_exists(public_path($setting->new_arrivale_image))) {
                unlink(public_path($setting->new_arrivale_image));
                $setting->new_arrivale_image = null;
            }
        }

        // Update marquee text
        $setting->marquee_text = $request->marquee_text ?? null;

        $setting->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Homepage settings updated successfully.'
        ]);
    }

    public function pagesContent(Request $request)
    {
        $request->validate([
            'privacy_policy' => 'nullable|string',
            'refund_policy' => 'nullable|string',
            'term_condition' => 'nullable|string',
        ]);

        DB::table('pages_contents')->updateOrInsert(
            ['id' => 1],
            [
                'privacy_policy' => $request->privacy_policy,
                'refund_policy' => $request->refund_policy,
                'terms' => $request->term_condition,
                'warranty_text' => $request->warranty,
                'servicing_text' => $request->servicing_text,
                'updated_at' => now()
            ]
        );

        return response()->json(['message' => 'Updated successfully']);
    }


}
