<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\EnquiryLeadStage;

class EnquiryLeadStageController extends Controller
{
    public function index()
    {
        $stages = EnquiryLeadStage::paginate(20);

        $page_data['stages'] = $stages;
        $page_data['view_path'] = 'enquiry_lead_stages.index';
    
        return view('backend.index', $page_data);
        //return view('admin.enquiry_lead_stages.index', compact('stages'));
    }

    public function create()
    {
        $page_data['view_path'] = 'enquiry_lead_stages.create';
    
        return view('backend.index', $page_data);
        //return view('admin.enquiry_lead_stages.create');
        
    }

    public function store(Request $request)
    {
        $request->validate([
            'stage_name' => 'required|string|max:255',
            'for_role' => 'required|in:buyer,seller,both'
        ]);

        EnquiryLeadStage::create($request->all());

        return redirect()->route('enquiry-lead-stages.index')->with('success', 'Lead Stage Created Successfully');
    }

    public function edit(EnquiryLeadStage $enquiryLeadStage)
    {
        $page_data['enquiryLeadStage'] = $enquiryLeadStage;
        $page_data['view_path'] = 'enquiry_lead_stages.edit';
    
        return view('backend.index', $page_data);
        //return view('admin.enquiry_lead_stages.edit', compact('enquiryLeadStage'));
    }

    public function update(Request $request, EnquiryLeadStage $enquiryLeadStage)
    {
        $request->validate([
            'stage_name' => 'required|string|max:255',
            'for_role' => 'required|in:buyer,seller,both'
        ]);

        $enquiryLeadStage->update($request->all());

        return redirect()->route('enquiry-lead-stages.index')->with('success', 'Lead Stage Updated Successfully');
    }

    public function destroy(EnquiryLeadStage $enquiryLeadStage)
    {
        $enquiryLeadStage->delete();

        return redirect()->route('enquiry-lead-stages.index')->with('success', 'Lead Stage Deleted Successfully');
    }
}
