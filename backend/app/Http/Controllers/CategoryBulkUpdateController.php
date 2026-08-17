<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Pagecategory;

class CategoryBulkUpdateController extends Controller
{
    public function showForm()
    {
        $page_data['categories'] = []; // Optional: add pagination for large data

        $page_data['view_path'] = 'page.bulk-category-update';
        return view('backend.index',$page_data);
    }

    public function handleUpdate(Request $request)
{
    $request->validate([
        'old_category_id' => 'required|exists:pagecategories,id',
        'new_category_id' => 'required|exists:pagecategories,id|different:old_category_id',
    ]);

    $oldCategoryId = $request->old_category_id;
    $newCategoryId = $request->new_category_id;
    $batchSize = 1000;

    try {
        DB::beginTransaction();

        // Update pivot table: page_category
        DB::table('page_category')
            ->where('category_id', $oldCategoryId)
            ->select('id', 'page_id') // Must include primary key 'id'
            ->orderBy('id')
            ->chunkById($batchSize, function ($rows) use ($oldCategoryId, $newCategoryId) {
                $pageIds = $rows->pluck('page_id')->toArray();

                // Delete old mapping
                DB::table('page_category')
                    ->whereIn('page_id', $pageIds)
                    ->where('category_id', $oldCategoryId)
                    ->delete();

                // Prevent duplicate insertions
                $existing = DB::table('page_category')
                    ->whereIn('page_id', $pageIds)
                    ->where('category_id', $newCategoryId)
                    ->pluck('page_id')
                    ->toArray();

                $toInsert = [];
                foreach ($pageIds as $pageId) {
                    if (!in_array($pageId, $existing)) {
                        $toInsert[] = [
                            'page_id' => $pageId,
                            'category_id' => $newCategoryId,
                        ];
                    }
                }

                if (!empty($toInsert)) {
                    DB::table('page_category')->insert($toInsert);
                }
            });

        // Also update 'category_id' field in 'pages' table (comma-separated values)
        $pagesToUpdate = DB::table('pages')
            ->whereRaw("? = ANY(string_to_array(category_id, ',')::bigint[])", [$oldCategoryId])
            ->select('id', 'category_id')
            ->get();

        foreach ($pagesToUpdate as $page) {
            $categoryIds = explode(',', $page->category_id);

            // Replace old category with new
            $categoryIds = array_map(function ($id) use ($oldCategoryId, $newCategoryId) {
                return $id == $oldCategoryId ? $newCategoryId : $id;
            }, $categoryIds);

            // Remove any duplicates
            $categoryIds = array_unique($categoryIds);

            DB::table('pages')
                ->where('id', $page->id)
                ->update(['category_id' => implode(',', $categoryIds)]);
        }

        DB::commit();

        return back()->with('success', 'Category updated for all pages successfully.');

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', 'Failed: ' . $e->getMessage());
    }
}




    public function ajaxSearchCategories(Request $request)
    {
        $data = [];
        if($request->has('q')){
        $search = $request->q;
        $data = DB::table("pagecategories")
        ->select("id","category_name")
        ->where('category_name','LIKE',"$search%")
        ->get();
        }
        return response()->json($data);
    }

}
