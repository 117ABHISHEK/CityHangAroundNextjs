<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Review;
use App\Services\UserActivityService;
class ReviewController extends Controller
{
    public function store(Request $request)
{
    //echo "123";exit;
    $request->validate([
        'marketplace_id' => 'required|exists:marketplaces,id',
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'nullable|string|max:1000',
    ]);

    Review::create([
        'user_id' => auth()->id(),
        'marketplace_id' => $request->marketplace_id,
        'type' => $request->type,
        'rating' => $request->rating,
        'review' => $request->review,
    ]);

    if (auth()->user()){
        app(UserActivityService::class)->log(auth()->user()->id, 'review', 'product',$request->marketplace_id,$request->marketplace_id);
     }
    

    return back()->with('success', 'Review submitted successfully.');
}


public function storepagesreview(Request $request)
{
    //echo "123";exit;
    $request->validate([
        'marketplace_id' => 'required|exists:pages,id',
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'nullable|string|max:1000',
    ]);

    Review::create([
        'user_id' => auth()->id(),
        'marketplace_id' => $request->marketplace_id,
        'type' => $request->type,
        'rating' => $request->rating,
        'review' => $request->review,
    ]);

    if (auth()->user()){
        app(UserActivityService::class)->log(auth()->user()->id, 'review', 'page',$request->marketplace_id,$request->marketplace_id);
     }
    

    return back()->with('success', 'Review submitted successfully.');
}


public function storeblog(Request $request)
{
    //echo "123";exit;
    $request->validate([
        'marketplace_id' => 'required|exists:blogs,id',
        'rating' => 'required|integer|min:1|max:5',
        'review' => 'nullable|string|max:1000',
    ]);

    Review::create([
        'user_id' => auth()->id(),
        'marketplace_id' => $request->marketplace_id,
        'type' => $request->type,
        'rating' => $request->rating,
        'review' => $request->review,
    ]);


     if (auth()->user()){
        app(UserActivityService::class)->log(auth()->user()->id, 'review', 'blog',$request->marketplace_id,$request->marketplace_id);
     }
    

    return back()->with('success', 'Review submitted successfully.');
}

public function loadMoreReviews(Request $request, $id)
{
    $offset = (int) $request->input('offset', 0);
    $limit = (int) $request->input('limit', 5);

    $reviews = Review::where('marketplace_id', $id)
        ->with('user')
        ->where('type', 'product')
        ->latest()
        ->skip($offset)
        ->take($limit)
        ->get();

    return response()->json($reviews);
}


public function loadMoreblogReviews(Request $request, $id)
{
    $offset = (int) $request->input('offset', 0);
    $limit = (int) $request->input('limit', 5);

    $reviews = Review::where('marketplace_id', $id)
        ->with('user')
        ->where('type', 'blog')
        ->latest()
        ->skip($offset)
        ->take($limit)
        ->get();

    return response()->json($reviews);
}



public function loadMorepagesReviews(Request $request, $id)
{
    $offset = (int) $request->input('offset', 0);
    $limit = (int) $request->input('limit', 5);

    $reviews = Review::where('marketplace_id', $id)
        ->with('user')
        ->where('type', 'pages')
        ->latest()
        ->skip($offset)
        ->take($limit)
        ->get();

    return response()->json($reviews);
}

}
