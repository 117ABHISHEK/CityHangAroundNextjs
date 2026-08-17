<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactQuery;
use Carbon\Carbon;
class ContactQueryController extends Controller
{
    //

   public function index(Request $request)
{
    $query = ContactQuery::query();

    if ($request->has(['from', 'to'])) {
        $from = Carbon::parse($request->from)->startOfDay();
        $to = Carbon::parse($request->to)->endOfDay();
        $query->whereBetween('created_at', [$from, $to]);
    }

    $page_data['queries'] = $query->latest()->paginate(20);
    $page_data['view_path'] = 'contact_queries.index';

    return view('backend.index', $page_data);
}
}
