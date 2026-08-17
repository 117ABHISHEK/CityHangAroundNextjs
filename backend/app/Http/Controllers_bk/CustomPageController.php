<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomPage;
use DB;
use App\Helpers\CityHelper;
class CustomPageController extends Controller {
    // Show all pages
    public function index() {
        $pages = CustomPage::all();
        $page_data['pages'] =$pages;
        $page_data['view_path'] ='custom_pages.index';
        return view('backend.index',$page_data);
       // return view('custom_pages.index', compact('pages'));
    }

    public function toggleStatus($id) {
        $page = CustomPage::findOrFail($id);
        $page->status = !$page->status; // Toggle the status
        $page->save();
    
        return response()->json([
            'success' => true,
            'status' => $page->status
        ]);
    }
    

    // Show form to create a new page
    public function create() {
        $page_data['view_path'] ='custom_pages.create';
        return view('backend.index',$page_data);
        //return view('custom_pages.create');
    }

    // Store a new page
    public function store(Request $request) {
        //echo "123";exit;
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        CustomPage::create([
            'title' => $request->title,
            'slug' => str_slug($request->title),
            'content' => $request->content,
        ]);

        return redirect()->route('custom_pages.list')->with('success', 'Page created successfully!');
    }

    // Show edit form
    public function edit($id) {
        $page = CustomPage::findOrFail($id);
        $page_data['page'] =$page;
        $page_data['view_path'] ='custom_pages.edit';
        return view('backend.index',$page_data);
        //return view('custom_pages.edit', compact('page'));
    }

    // Update the page
    public function update(Request $request, $id) {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required',
        ]);

        $page = CustomPage::findOrFail($id);
        $page->update([
            'title' => $request->title,
           'slug' => str_slug($request->title),
            'content' => $request->content,
        ]);

        return redirect()->route('custom_pages.list')->with('success', 'Page updated successfully!');
    }

    // Delete the page
    public function destroy($id) {
        $page = CustomPage::findOrFail($id);
        $page->delete();
        return redirect()->route('custom_pages.list')->with('success', 'Page deleted successfully!');
    }

    // Show a custom page dynamically
    public function show($slug) {
        $page_data['all_cities'] = CityHelper::getActiveCities();
        $page = CustomPage::where('slug', $slug)->firstOrFail();

        $page_data['page'] =$page;
        $page_data['view_path'] = 'frontend.custom_pages.show';
        return view('frontend.index', $page_data);
        //return view('custom_pages.show', compact('page'));
    }
}
