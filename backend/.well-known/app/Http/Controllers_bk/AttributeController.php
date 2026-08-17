<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Attribute;
use Illuminate\Http\Request;

class AttributeController extends Controller
{
    public function index(Request $request)
{
    // Fetch only parent categories (category_parent_id = 0)
    $categories = Category::where('category_parent_id', 0)->get();
    $selectedCategory = null;
    $attributes = [];

    if ($request->category_id) {
        // Fetch the selected category (Ensure it's a parent category)
        $selectedCategory = Category::where('id', $request->category_id)
                                    ->where('category_parent_id', 0) // Make sure the selected category is a parent
                                    ->first();

        if ($selectedCategory) {
            // Fetch attributes for the selected parent category
            $attributes = $selectedCategory->attributes;
        }
    }

    //print_r($attributes );exit;

    // Pass data to the view
    $page_data['categories'] = $categories;
    $page_data['selectedCategory'] = $selectedCategory;
    $page_data['attributes'] = $attributes;
    $page_data['view_path'] = 'attributes.index';

    return view('backend.index', $page_data);
}


    public function create(Request $request)
    {
        $categories = Category::where('category_parent_id',0)->get();
        $page_data['categories'] = $categories;
        $page_data['category_id'] = $request->category_id;
        //echo $page_data['category_id'];exit;
        $page_data['view_path'] ='attributes.create';
        return view('backend.index', $page_data);
    }

    public function searchCategories(Request $request)
    {
        // Search for categories based on the 'q' query parameter
        $query = $request->input('q');
        $categories = Category::where('product_category_name', 'like', '%' . $query . '%')
                                ->get(['id', 'product_category_name as text']);  // 'text' will be used by Select2

        // Return the categories in a format that Select2 expects
        return response()->json(['results' => $categories]);
    }

    // Store the attribute and its values
    public function store(Request $request)
{
    // 1. Validate request
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'values' => 'required|array',
        'values.*' => 'string|max:255',
    ]);

    // 2. Create the attribute for the main category
    $attribute = Attribute::create([
        'name' => $request->name,
        'category_id' => $request->category_id,
    ]);

    // 3. Create values once
    foreach ($request->values as $value) {
        $attribute->values()->create([
            'value' => $value
        ]);
    }

    // 4. Get all descendant category IDs
    $allCategoryIds = $this->getAllDescendantCategoryIds($request->category_id);

    // 5. Assign same attribute to subcategories
    foreach ($allCategoryIds as $catId) {
        Attribute::create([
            'name' => $request->name,
            'category_id' => $catId,
        ])->values()->createMany(
            collect($request->values)->map(fn($v) => ['value' => $v])->toArray()
        );
    }

    return redirect()
        ->route('admin.view.attributes.index', ['category_id' => $request->category_id])
        ->with('success', 'Attribute and values created for all subcategories.');
}
private function getAllDescendantCategoryIds($categoryId)
{
    $ids = [];

    $children = Category::where('category_parent_id', $categoryId)->get();

    foreach ($children as $child) {
        $ids[] = $child->id;
        $ids = array_merge($ids, $this->getAllDescendantCategoryIds($child->id));
    }

    return $ids;
}



    // Show the form to edit the attribute
    public function edit($id)
    {
        
        $attribute = Attribute::findOrFail($id);
        $categories = Category::all();
        $page_data['attribute'] = $attribute;
        $page_data['categories'] = $categories;
        $page_data['view_path'] ='attributes.edit';
        return view('backend.index', $page_data);
    }

    public function update(Request $request, $id)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'values' => 'required|array',
    ]);

    // 1. Find original attribute
    $originalAttribute = Attribute::findOrFail($id);

    // 2. Fetch all descendant category IDs (subcategories only)
    $subCategoryIds = $this->getAllDescendantCategoryIds($request->category_id);

    // 3. Get all related attributes (same name in parent + subs)
    $attributes = Attribute::whereIn('category_id', array_merge([$request->category_id], $subCategoryIds))
                           ->where('name', $originalAttribute->name)
                           ->get();

    foreach ($attributes as $attribute) {
        // ⚠️ Only update `category_id` if it's the original attribute
        if ($attribute->id == $originalAttribute->id) {
            $attribute->update([
                'name' => $request->name,
                'category_id' => $request->category_id, // Only change if it's original
            ]);
        } else {
            // Just update name for subcategories, not category_id
            $attribute->update([
                'name' => $request->name,
            ]);
        }

        // Handle attribute values
        foreach ($request->values as $key => $val) {
            if ($key === 'new') {
                foreach ($val as $newVal) {
                    if (!empty(trim($newVal))) {
                        $attribute->values()->create(['value' => $newVal]);
                    }
                }
            } else {
                if (!empty(trim($val))) {
                    $attribute->values()->where('id', $key)->update(['value' => $val]);
                }
            }
        }
    }

    return redirect()->route('admin.view.attributes.index', ['category_id' => $request->category_id])
                     ->with('success', 'Attribute updated successfully for selected category and subcategories.');
}

    
    

public function destroy($id)
{
    $attribute = Attribute::findOrFail($id);

    // Get all subcategory IDs including current
    $allCategoryIds = $this->getAllDescendantCategoryIds($attribute->category_id);
    $allCategoryIds[] = $attribute->category_id;

    // Find all attributes with same name under subcategories
    $attributes = Attribute::where('name', $attribute->name)
                           ->whereIn('category_id', $allCategoryIds)
                           ->get();

    foreach ($attributes as $attr) {
        $attr->values()->delete(); // delete values first (if needed)
        $attr->delete();
    }

    return redirect()->route('admin.view.attributes.index', ['category_id' => $attribute->category_id])
                     ->with('success', 'Attribute and all related entries deleted successfully.');
}


}

