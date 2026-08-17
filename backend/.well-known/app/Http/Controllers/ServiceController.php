<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $page_data['services'] = Service::all();
        $page_data['view_path'] ='services.index';
        return view('backend.index',$page_data);
        //return view('admin.services.index', compact('services'));
    }

    public function create()
    {
        $page_data['view_path'] ='services.create';
        return view('backend.index',$page_data);
        //return view('admin.services.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:services',
            'label' => 'nullable|string',
            'approval_type' => 'required|in:auto,manual',
            'is_enabled' => 'required|boolean',
        ]);

        Service::create($request->all());

        return redirect()->route('admin.services.index')->with('success', 'Service created.');
    }

    public function edit(Service $service)
    {
        return view('admin.services.edit', compact('service'));
    }

    public function update(Request $request, Service $service)
    {
        $request->validate([
            'label' => 'nullable|string',
            'approval_type' => 'required|in:auto,manual',
            'is_enabled' => 'required|boolean',
        ]);

        $service->update($request->only(['label', 'approval_type', 'is_enabled']));

        return redirect()->route('admin.services.index')->with('success', 'Service updated.');
    }
}
