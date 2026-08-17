<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\SubscriptionFeature;
use App\Models\SubscriptionFeatureMapping;
use Illuminate\Http\Request;

class SubscriptionFeatureMappingController extends Controller
{
    public function index(Request $request)
{
    $query = SubscriptionFeatureMapping::with(['subscription', 'feature']);

    // Apply filters
    if ($request->filled('subscription_id')) {
        $query->where('subscription_id', $request->subscription_id);
    }

    if ($request->filled('feature_id')) {
        $query->where('feature_id', $request->feature_id);
    }

    if ($request->filled('start_date')) {
        $query->whereDate('created_at', '>=', $request->start_date);
    }

    if ($request->filled('end_date')) {
        $query->whereDate('created_at', '<=', $request->end_date);
    }

    // Paginate results (10 per page)
    $mappings = $query->paginate(10);

    $page_data = [
        'mappings' => $mappings,
        'subscriptions' => Subscription::all(), 
        'features' => SubscriptionFeature::all(),
        'view_path' => 'mappings.index',
    ];

    return view('backend.index', $page_data);
}


    public function create()
    {
        $subscriptions = Subscription::all();
        $features = SubscriptionFeature::all();

        $page_data['subscriptions'] = $subscriptions;
        $page_data['features'] = $features;

        $page_data['view_path'] = 'mappings.create';
        return view('backend.index', $page_data);
       // return view('admin.mappings.create', compact('subscriptions', 'features'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'feature_id' => 'required|exists:subscription_features,id',
            'value' => 'required'
        ]);

        SubscriptionFeatureMapping::create($request->all());
        return redirect()->route('admin.mappings.index')->with('success', 'Feature Mapping Created Successfully!');
    }

    public function edit(SubscriptionFeatureMapping $mapping)
    {
        $subscriptions = Subscription::all();
        $features = SubscriptionFeature::all();


        $page_data['subscriptions'] =$subscriptions;
        $page_data['features'] =$features;
        $page_data['mapping'] =$mapping;
        $page_data['view_path'] = 'mappings.edit';
        return view('backend.index', $page_data);

        //return view('admin.mappings.edit', compact('mapping', 'subscriptions', 'features'));
    }

    public function update(Request $request, SubscriptionFeatureMapping $mapping)
    {
        $request->validate([
            'subscription_id' => 'required|exists:subscriptions,id',
            'feature_id' => 'required|exists:subscription_features,id',
            'value' => 'required'
        ]);

        $mapping->update($request->all());
        return redirect()->route('admin.mappings.index')->with('success', 'Feature Mapping Updated Successfully!');
    }

    public function destroy(SubscriptionFeatureMapping $mapping)
    {
        $mapping->delete();
        return redirect()->route('admin.mappings.index')->with('success', 'Feature Mapping Deleted Successfully!');
    }
}
