<?php

namespace App\Http\Controllers;
use App\Models\ContactQuery;
use Illuminate\Http\Request;

class ContactController extends Controller
{
   public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'phone' => 'required|string|max:20',
        'email' => 'required|email',
        'city' => 'required|string|max:100',
        'query' => 'required|string',
    ]);

    ContactQuery::create($validated);

    return back()->with('success', 'Query submitted successfully!');
}
}
