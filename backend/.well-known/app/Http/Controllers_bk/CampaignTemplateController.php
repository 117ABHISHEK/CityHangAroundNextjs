<?php
namespace App\Http\Controllers;

use App\Models\CampaignTemplate;
use Illuminate\Http\Request;

class CampaignTemplateController extends Controller
{
    public function index()
    {
        $page_data['templates'] = CampaignTemplate::all();
        $page_data['view_path'] = 'campaign_templates.index';
        return view('backend.index',$page_data);
        //return view('campaign_templates.index', compact('templates'));
    }

    public function create()
    {
        $page_data['view_path'] = 'campaign_templates.create';
        return view('backend.index',$page_data);
        //return view('campaign_templates.create');
    }

    public function store(Request $request)
    {
       
        $request->validate([
            'name' => 'required|string|max:255',
            'html_content' => 'required',
            'created_by' => 'required',
        ]);

        CampaignTemplate::create([
            'name' => $request->input('name'),
            'html_content' => $request->input('html_content'),
            'created_by' => auth()->id(), // Set the created_by field
        ]);
    

        return redirect()->route('admin.campaign_templates.index')->with('success', 'Template created successfully!');
    }

    public function edit($id)
    {
       
        $page_data['template'] = CampaignTemplate::findOrFail($id);
        $page_data['view_path'] = 'campaign_templates.edit';
        return view('backend.index',$page_data);
        //return view('campaign_templates.edit', compact('template'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'html_content' => 'required',
        ]);

        $template = CampaignTemplate::findOrFail($id);
        $template->update($request->all());

        return redirect()->route('admin.campaign_templates.index')->with('success', 'Template updated successfully!');
    }

    public function destroy($id)
    {
        $template = CampaignTemplate::findOrFail($id);
        $template->delete();

        return redirect()->route('admin.campaign_templates.index')->with('success', 'Template deleted successfully!');
    }
}
