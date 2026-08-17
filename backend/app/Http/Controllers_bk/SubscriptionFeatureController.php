<?php
namespace App\Http\Controllers;

use App\Models\SubscriptionFeature;
use Illuminate\Http\Request;

class SubscriptionFeatureController extends Controller
{
    public function index(Request $request)
{
    $query = SubscriptionFeature::query();

    // Apply filters if available
    if ($request->filled('name')) {
        $query->where('feature_name', 'LIKE', '%' . $request->name . '%');
    }

    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    // Fetch filtered & paginated results
    $features = $query->latest()->paginate(10);

    $page_data['features'] = $features;
    $page_data['view_path'] = 'features.index';

    return view('backend.index', $page_data);
}


    public function create()
    {

        $page_data['view_path'] = 'features.create';
        return view('backend.index', $page_data);
        //return view('admin.features.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'feature_name' => 'required|unique:subscription_features'
        ]);

        SubscriptionFeature::create($request->all());
        return redirect()->route('admin.features.index')->with('success', 'Feature Added Successfully!');
    }

    public function edit(SubscriptionFeature $feature)
    {

        $page_data['feature'] =$feature;
        $page_data['view_path'] = 'features.edit';
        return view('backend.index', $page_data);
        //return view('admin.features.edit', compact('feature'));
    }

    public function update(Request $request, SubscriptionFeature $feature)
    {
        $request->validate([
            'feature_name' => 'required|unique:subscription_features,feature_name,' . $feature->id
        ]);

        $feature->update($request->only('feature_name'));
        return redirect()->route('admin.features.index')->with('success', 'Feature Updated Successfully!');
    }


    public function destroy(SubscriptionFeature $feature)
    {
        $feature->delete();
        return redirect()->route('admin.features.index')->with('success', 'Feature Deleted Successfully!');
    }
}
