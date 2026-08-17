<?php

namespace App\Http\Controllers;

use App\Models\MailingList;
use App\Models\Page;
use App\Models\City;
use App\Models\Area;
use App\Models\Pagecategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MailingListController extends Controller
{
    public function index(Request $request)
    {
        $query = Page::query()
            ->with(['city', 'area', 'categories', 'user'])
            ->doesntHave('mailingLists')
            ->whereNotNull('item_email')
            ->where('item_email', '!=', '');

        $query = $this->applyPageFilters($query, $request);

        $page_data['pages'] = $query->paginate(100);
        $page_data['cities'] = City::whereIn('id', Page::select('city_id'))->get();
        $page_data['areas'] = Area::whereIn('id', Page::select('area_id'))->get();
        $page_data['categories'] = Pagecategory::whereHas('pages')->get();
        $page_data['availableLists'] = MailingList::all();
        $page_data['view_path'] = 'mailing_lists.index';

        return view('backend.index', $page_data);
    }

    public function handleBulkAction(Request $request)
{
    $action = $request->input('action');
  
//dd($request->input('pages_json'));
   
    // $request->validate([
    //     'new_list_name' => 'required|string|max:255|unique:mailing_lists,name',
    // ]);
    
    // Decode the JSON string from the input and set it to $selectedPages
    $selectedPages = $request->input('pages_json') ? json_decode($request->input('pages_json'), true) : [];
    //print_r($selectedPages);exit;
    // Validate that $selectedPages is an array
    if (!is_array($selectedPages)) {
        return redirect()->back()->withErrors(['Invalid page selection']);
    }

    switch ($action) {
        case 'create':
            $request->validate([
                'new_list_name' => 'required|string|max:255',
            ]);

            $newMailingList = MailingList::create([
                'name' => $request->new_list_name,
                'created_by' => auth()->id(),
                'updated_by' => auth()->id(),
            ]);

            if (!empty($selectedPages)) {
                $newMailingList->pages()->attach($selectedPages);
            }
            break;

        case 'edit':
            $request->validate([
                'existing_list' => 'required|exists:mailing_lists,id',
                'updated_name' => 'required|string|max:255',
            ]);

            $mailingList = MailingList::findOrFail($request->existing_list);
            $mailingList->update([
                'name' => $request->updated_name,
                'updated_by' => auth()->id(),
            ]);
            $mailingList->pages()->sync($selectedPages);
            break;

        case 'delete':
             // echo $action;exit;
            $request->validate([
                'delete_list' => 'required|exists:mailing_lists,id',
            ]);

            $listToDelete = MailingList::findOrFail($request->delete_list);
            $listToDelete->pages()->detach();
            $listToDelete->delete();
            break;

        case 'transfer':
            $request->validate([
                'source_list' => 'required|exists:mailing_lists,id',
                'transfer_list' => 'required|exists:mailing_lists,id',
            ]);

            $sourceList = MailingList::find($request->source_list);
            $targetList = MailingList::find($request->transfer_list);

            $pagesToTransfer = $sourceList->pages()
                ->whereIn('pages.id', $selectedPages)
                ->get();

            $targetList->pages()->syncWithoutDetaching($pagesToTransfer->pluck('id')->toArray());
            $sourceList->pages()->detach($pagesToTransfer->pluck('id')->toArray());
            break;

        default:
            return redirect()->back()->withErrors(['Invalid action']);
    }

    return redirect()->route('admin.mailing_lists.index')->with('success', 'Action completed successfully');
}


    // public function handleBulkAction(Request $request)
    // {
    //     $action = $request->input('action');
    //     $selectedPages = $request->input('pages', []);

    //     switch ($action) {
    //         case 'create':
    //             $request->validate([
    //                 'new_list_name' => 'required|string|max:255',
    //             ]);

    //             $newMailingList = MailingList::create([
    //                 'name' => $request->new_list_name,
    //                 'created_by' => auth()->id(),
    //                 'updated_by' => auth()->id(),
    //             ]);

    //             if (!empty($selectedPages)) {
    //                 $newMailingList->pages()->attach($selectedPages);
    //             }
    //             break;

    //         case 'edit':
    //             $request->validate([
    //                 'existing_list' => 'required|exists:mailing_lists,id',
    //                 'updated_name' => 'required|string|max:255',
    //             ]);

    //             $mailingList = MailingList::findOrFail($request->existing_list);
    //             $mailingList->update([
    //                 'name' => $request->updated_name,
    //                 'updated_by' => auth()->id(),
    //             ]);
    //             $mailingList->pages()->sync($selectedPages);
    //             break;

    //         case 'delete':
    //             $request->validate([
    //                 'delete_list' => 'required|exists:mailing_lists,id',
    //             ]);

    //             $listToDelete = MailingList::findOrFail($request->delete_list);
    //             $listToDelete->pages()->detach();
    //             $listToDelete->delete();
    //             break;

    //         case 'transfer':
    //             $request->validate([
    //                 'source_list' => 'required|exists:mailing_lists,id',
    //                 'transfer_list' => 'required|exists:mailing_lists,id',
    //             ]);

    //             $sourceList = MailingList::find($request->source_list);
    //             $targetList = MailingList::find($request->transfer_list);

    //             $pagesToTransfer = $sourceList->pages()
    //                 ->whereIn('pages.id', $selectedPages)
    //                 ->get();

    //             $targetList->pages()->syncWithoutDetaching($pagesToTransfer->pluck('id')->toArray());
    //             $sourceList->pages()->detach($pagesToTransfer->pluck('id')->toArray());
    //             break;

    //         default:
    //             return redirect()->back()->withErrors(['Invalid action']);
    //     }

    //     return redirect()->route('admin.mailing_lists.index')->with('success', 'Action completed successfully');
    // }

    public function getPagesByList($listId, Request $request)
{
    $list = MailingList::findOrFail($listId);
    $selectedPageIds = $list->pages()->pluck('pages.id')->toArray();

    $query = Page::query()
        ->with(['city', 'area', 'user', 'categories'])
        ->whereNotNull('item_email')
        ->where('item_email', '!=', '');

    $action = $request->input('action');

    if ($action === 'transfer') {
        // Only include pages already attached to this list
        $query->whereIn('id', $selectedPageIds);
    } elseif ($action !== 'create') {
        // Exclude pages linked to other lists
        $query->whereDoesntHave('mailingLists', function ($q) use ($listId) {
            $q->where('mailing_lists.id', '!=', $listId);
        });
    }

    $query = $this->applyPageFilters($query, $request);
    $pages = $query->paginate(100);

    $formattedPages = $pages->getCollection()->transform(function ($page) use ($selectedPageIds) {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'selected' => in_array($page->id, $selectedPageIds),
            'city_name' => optional($page->city)->city_name ?? '',
            'area_name' => optional($page->area)->area_name ?? '',
            'user_name' => optional($page->user)->name ?? '',
            'item_email' => $page->item_email,
            'categories' => $page->categories->pluck('category_name')->toArray(),
        ];
    });

    return response()->json([
        'pages' => $formattedPages,
        'totalPages' => $pages->lastPage(),
        'meta' => [
            'current_page' => $pages->currentPage(),
            'last_page' => $pages->lastPage(),
            'per_page' => $pages->perPage(),
            'total' => $pages->total(),
        ],
        'pagination' => (string) $pages->links('pagination::bootstrap-5'),
    ]);
}

    

//     public function getAllPages(Request $request)
// {
//     $query = Page::query()
//         ->with(['city', 'area', 'user', 'categories'])
//         ->doesntHave('mailingLists')
//         ->whereNotNull('item_email')
//         ->where('item_email', '!=', '');

//     // Apply filters
//     $query = $this->applyPageFilters($query, $request);

//     $pages = $query->paginate(100);

//     $formattedPages = $pages->getCollection()->transform(function ($page) {
//         return [
//             'id' => $page->id,
//             'title' => $page->title,
//             'selected' => false,
//             'city_name' => optional($page->city)->city_name ?? '',
//             'area_name' => optional($page->area)->area_name ?? '',
//             'user_name' => optional($page->user)->name ?? '',
//             'item_email' => $page->item_email,
//             'categories' => $page->categories->pluck('category_name')->toArray(),
//         ];
//     });

//     return response()->json([
//         'pages' => $formattedPages,
//         'totalPages' => $pages->lastPage(),
//         'meta' => [
//             'current_page' => $pages->currentPage(),
//             'last_page' => $pages->lastPage(),
//             'per_page' => $pages->perPage(),
//             'total' => $pages->total(),
//         ],
//         'pagination' => (string) $pages->links('pagination::bootstrap-5'),
//     ]);
// }

// private function applyPageFilters($query, Request $request)
// {
//     if ($request->filled('city_id')) {
//         $cityIds = explode(',', $request->input('city_id'));
//         $query->whereIn('city_id', $cityIds);
//     }

//     if ($request->filled('area_id')) {
//         $areaIds = explode(',', $request->input('area_id'));
//         $query->whereIn('area_id', $areaIds);
//     }

//     if ($request->filled('category_id')) {
//         $categoryIds = explode(',', $request->input('category_id'));
//         $query->whereHas('categories', function ($q) use ($categoryIds) {
//             $q->whereIn('pagecategories.id', $categoryIds);
//         });
//     }

//     return $query;
// }


public function getAllPages(Request $request)
{
    $query = Page::query()
        ->with(['city', 'area', 'user', 'categories'])
        ->doesntHave('mailingLists')
        ->whereNotNull('item_email')
        ->where('item_email', '!=', '');

    

    // Apply other filters (city, area, category)
    $this->applyPageFilters($query, $request);

    $pages = $query->paginate(100);

    $formattedPages = $pages->getCollection()->transform(function ($page) {
        return [
            'id' => $page->id,
            'title' => $page->title,
            'selected' => false,
            'city_name' => optional($page->city)->city_name ?? '',
            'area_name' => optional($page->area)->area_name ?? '',
            'user_name' => optional($page->user)->name ?? '',
            'item_email' => $page->item_email,
            'categories' => $page->categories->pluck('category_name')->toArray(),
        ];
    });

    return response()->json([
        'pages' => $formattedPages,
        'totalPages' => $pages->lastPage(),
        'meta' => [
            'current_page' => $pages->currentPage(),
            'last_page' => $pages->lastPage(),
            'per_page' => $pages->perPage(),
            'total' => $pages->total(),
        ],
        'pagination' => (string) $pages->links('pagination::bootstrap-5'),
    ]);
}

private function applyPageFilters($query, Request $request)
{
    if ($request->filled('city_id')) {
        $cityIds = explode(',', $request->input('city_id'));
        $query->whereIn('city_id', $cityIds);
    }

    if ($request->filled('area_id')) {
        $areaIds = explode(',', $request->input('area_id'));
        $query->whereIn('area_id', $areaIds);
    }

    if ($request->filled('category_id')) {
        $categoryIds = explode(',', $request->input('category_id'));
        $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('pagecategories.id', $categoryIds);
        });
    }

    // Apply filter tags (handle single or multiple tags)
    if ($request->filled('tags')) {
        $tags = is_array($request->tags) ? $request->tags : [$request->tags]; // ensure array
        
        foreach ($tags as $tag) {
            switch ($tag) {
                case 'banner_missing':
                    $query->where(function ($q) {
                        $q->whereNull('coverphoto')->orWhere('coverphoto', '');
                    });
                    break;

                case 'logo_missing':
                    $query->where(function ($q) {
                        $q->whereNull('logo')->orWhere('logo', '');
                    });
                    break;

                case 'no_product':
                    $query->whereDoesntHave('products');
                    break;

                case 'incomplete':
                    $query->where(function ($q) {
                        $q->whereNull('coverphoto')
                          ->orWhere('coverphoto', '')
                          ->orWhereNull('logo')
                          ->orWhere('logo', '')
                          ->orWhereNull('address')
                          ->orWhere('address', '')
                          ->orWhereNull('pincode')
                          ->orWhere('pincode', '')
                          ->orWhereNull('item_email')
                          ->orWhere('item_email', '')
                          ->orWhereNull('item_phone')
                          ->orWhere('item_phone', '')
                          ->orWhereDoesntHave('products');
                    });
                    break;
            }
        }
    }

    return $query;
}





}
