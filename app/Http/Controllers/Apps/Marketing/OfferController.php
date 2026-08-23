<?php

namespace App\Http\Controllers\Apps\Marketing;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\DataTables\OfferDataTable;
use App\Models\Offer;
use App\Models\OfferLink;
use Illuminate\Support\Str;


class OfferController extends Controller
{
    public function index(OfferDataTable $dataTable)
    {
        return $dataTable->render('pages.apps.offer.list');
    }

    public function create()
    {
        return view('pages.apps.offer.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'thumb_image' => 'required|image',
            'start_at' => 'required',
            'expire_date' => 'required|date|after:start_at|after:today',
            'descrip' => 'required',
            'details' => 'nullable',
        ]);

        if ($request->hasFile('thumb_image')) {
            $randomString = (string) Str::uuid();
            $imagePath = fileUpload($request->file('thumb_image'),'offer',$randomString);
        }

        $links = json_encode($request->link_title);

        $data = new Offer();
        $data->title = $request->title;
        $data->descrip = $request->descrip;
        $data->image = $imagePath ?? null;
        $data->start_at = $request->start_at;
        $data->expire_date = $request->expire_date;
        $data->details = $request->details;
        $data->status = true;

        $data->save();

        if ($request->filled('link_title')) {
            foreach ($request->link_title as $index => $link_title) {
                if (!empty($link_title) && !empty($request->link[$index])) {
                    OfferLink::create([
                        'offer_id' => $data->id,
                        'title'    => $link_title,
                        'link'     => $request->link[$index],
                    ]);
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Offer Added Successfully',
        ]);
    }

     public function edit($id)
    {
        $data = Offer::with('links')->findOrFail($id);
        $links = $data->links;

        return view('pages.apps.offer.edit', compact('data', 'links'));
    }

    public function update(Request $request, $id){
        $data = Offer::find($id);

        $request->validate([
            'title' => 'required',
            'thumb_image' => 'nullable|image',
            'start_at' => 'required',
            'expire_date' => 'required|date|after:start_at|after:today',
            'descrip' => 'required',
            'details' => 'nullable',
        ]);

        if ($request->hasFile('thumb_image')) {
            if ($data->image && file_exists(public_path($data->image))) {
                unlink(public_path($data->image));
            }
            $randomString = (string) Str::uuid();
            $imagePath = fileUpload($request->file('thumb_image'),'offer',$randomString);
        }

        $data->title = $request->title;
        $data->descrip = $request->descrip;
        $data->image = $imagePath ?? $data->image;
        $data->start_at = $request->start_at;
        $data->expire_date = $request->expire_date;
        $data->details = $request->details;
        $data->status = true;

        $data->save();

        if ($request->filled('link_title')) {
            foreach ($request->link_title as $index => $link_title) {
                if (!empty($link_title) && !empty($request->link[$index])) {
                    OfferLink::create([
                        'offer_id' => $data->id,
                        'title'    => $link_title,
                        'link'     => $request->link[$index],
                    ]);
                }
            }
        }

        $existingIds = [];

        if ($request->filled('link_title')) {
            foreach ($request->link_title as $index => $title) {
                if (!empty($title) && !empty($request->link[$index])) {
                    $linkId = $request->link_id[$index] ?? null;

                    if ($linkId) {
                        // Update existing link
                        $offerLink = OfferLink::where('offer_id', $data->id)->where('id', $linkId)->first();
                        if ($offerLink) {
                            $offerLink->update([
                                'title' => $title,
                                'link'  => $request->link[$index],
                            ]);
                            $existingIds[] = $offerLink->id;
                        }
                    } else {
                        // Create new link
                        $newLink = OfferLink::create([
                            'offer_id' => $data->id,
                            'title'    => $title,
                            'link'     => $request->link[$index],
                        ]);
                        $existingIds[] = $newLink->id;
                    }
                }
            }
        }

        // Delete removed links
        OfferLink::where('offer_id', $data->id)->whereNotIn('id', $existingIds)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Offer Updated Successfully',
        ]);

    }

    public function offers(){
       $offers = Offer::where('status', 1)
               ->where('expire_date', '>', today())
               ->latest()
               ->get();

        return view('frontend.pages.offers.offers', compact('offers'));
    }

    public function offerDetails($id){
       $offer = Offer::where('status', 1)
                ->where('id', $id)
               ->where('expire_date', '>', today())
               ->latest()
               ->first();

        if(!$offer){
            return view('frontend.pages.error.404');
        }

        return view('frontend.pages.offers.offer-details', compact('offer'));
    }

}
