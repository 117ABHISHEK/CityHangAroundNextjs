<?php
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // View all categories with lead prices
    public function index(Request $request)
    {
        $query = Category::where('category_parent_id', 0); // Get only parent categories
    
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
    
            // Search parent categories or their subcategories
            $query->where('product_category_name', 'LIKE', "%{$searchTerm}%")
                  ->orWhereHas('subcategories', function ($subQuery) use ($searchTerm) {
                      $subQuery->where('product_category_name', 'LIKE', "%{$searchTerm}%");
                  });
        }
    
        $categories = $query->paginate(10); // Paginate results (10 per page)
    
        $page_data = [
            'categories' => $categories,
            'search' => $request->search, // Keep search value in view
            'view_path' => 'product_category.categories.index',
        ];
    
        return view('backend.index', $page_data);
    }
    


    // Show edit form for lead price
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $page_data['category'] =$category;
        $page_data['view_path'] = 'product_category.categories.edit';
        return view('backend.index', $page_data);
        //return view('categories.edit', compact('category'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lead_price' => 'required|numeric|min:0',
        ]);
    
        $category = Category::findOrFail($id);
    
        // Update lead price for the selected category
        $category->update(['lead_price' => $request->lead_price]);
    
        // Update lead price for all subcategories recursively
        $this->updateSubcategoriesLeadPrice($category->id, $request->lead_price);
    
        return response()->json(['success' => true, 'message' => 'Lead Price updated successfully!', 'lead_price' => $category->lead_price]);
    }
    

    
    // Recursive function to update lead price for all subcategories
    private function updateSubcategoriesLeadPrice($parentId, $leadPrice)
    {
        $subcategories = Category::where('category_parent_id', $parentId)->get();
    
        foreach ($subcategories as $subcategory) {
            // Update lead price for each subcategory
            $subcategory->update(['lead_price' => $leadPrice]);
    
            // Recursively update its children
            $this->updateSubcategoriesLeadPrice($subcategory->id, $leadPrice);
        }
    }
    

    public function deleteLeadPrice($id)
    {
        $category = Category::findOrFail($id);
    
        // Set lead_price to NULL for the selected category
        $category->update(['lead_price' => null]);
    
        // Set lead_price to NULL for all subcategories recursively
        $this->deleteSubcategoriesLeadPrice($category->id);
    
        return redirect()->route('categories.index')->with('success', 'Lead Price removed successfully from category and subcategories!');
    }
    
    // Recursive function to delete lead price for all subcategories
    private function deleteSubcategoriesLeadPrice($parentId)
    {
        $subcategories = Category::where('category_parent_id', $parentId)->get();
    
        foreach ($subcategories as $subcategory) {
            // Set lead_price to NULL for each subcategory
            $subcategory->update(['lead_price' => null]);
    
            // Recursively remove lead price from its children
            $this->deleteSubcategoriesLeadPrice($subcategory->id);
        }
    }
    
}
