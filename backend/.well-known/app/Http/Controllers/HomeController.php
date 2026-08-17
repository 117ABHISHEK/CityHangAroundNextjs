<?php

public function index()
{
    $cityId = session('selected_city_id');

    if ($cityId) {
        $currentCity = City::find($cityId);
    } else {
        // default city if nothing selected
        $currentCity = City::where('city_slug', 'surat')->first();
        if ($currentCity) {
            session([
                'selected_city_id'   => $currentCity->id,
                'selected_city_name' => $currentCity->city_name,
                'selected_city_slug' => $currentCity->city_slug,
            ]);
        }
    }

    // Use $currentCity->id to filter
    $trendingListings = Listing::where('city_id', $currentCity->id)
        ->latest()
        ->take(10)
        ->get();

    // Similar filters for deals, events, etc.

    return view('frontend.home', compact('currentCity', 'trendingListings'));
}