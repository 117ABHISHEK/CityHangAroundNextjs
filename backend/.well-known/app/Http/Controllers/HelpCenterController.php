<?php

namespace App\Http\Controllers;

use App\Models\HelpArticle;
use Illuminate\Http\Request;
use DB;
use App\Helpers\CityHelper;
class HelpCenterController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');

        $page_data['all_cities'] = CityHelper::getActiveCities();
    
         $page_data['articles'] = HelpArticle::where('title', 'LIKE', "%$query%")
            ->orWhere('content', 'LIKE', "%$query%")
            ->get();
            $page_data['view_path'] = 'frontend.help.search';

            return view('frontend.index', $page_data);
        //return view('help.search', compact('articles', 'query'));
    }
    
    public function show($id)
    {
        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page_data['article'] = HelpArticle::findOrFail($id);
        $page_data['view_path'] = 'frontend.help.show';

            return view('frontend.index', $page_data);
        //return view('help.show', compact('article'));
    }

    public function liveSearch(Request $request)
    {
        $query = $request->input('query');

        $articles = HelpArticle::where('title', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%")
                        ->limit(10)
                        ->get();

        return response()->json($articles);
    }

}


