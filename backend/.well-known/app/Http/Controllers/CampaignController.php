<?php
namespace App\Http\Controllers;

use App\Models\Campaign;
use App\Models\MailingList;
use App\Models\CampaignTemplate;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use App\Jobs\SendCampaignEmailJob;


class CampaignController extends Controller
{
    // Show all campaigns
    public function index()
    {
         $page_data['campaigns'] = Campaign::with('template', 'mailingList')->get(); // Fetch campaigns with their templates and lists
        $page_data['view_path'] = 'campaigns.index';
        return view('backend.index',$page_data);
        //return view('campaigns.index', compact('campaigns'));
    }

    public function show($id)
{
}

    // Show form to create new campaign
    public function create()
    {
        $page_data['templates'] = CampaignTemplate::all();  // Get all email templates
        $page_data['mailingLists'] = MailingList::all();    // Get all mailing lists
        $page_data['view_path'] = 'campaigns.create';
        return view('backend.index',$page_data);
        //return view('campaigns.create', compact('templates', 'mailingLists'));
    }

    // Store new campaign
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'campaign_template_id' => 'required|exists:campaign_templates,id',
            'mailing_list_id' => 'required|exists:mailing_lists,id',
            'status' => 'required|in:draft,scheduled,sent',
            'scheduled_at' => 'nullable|date',
        ]);

        $campaign=Campaign::create([
            'name' => $request->name,
            'campaign_template_id' => $request->campaign_template_id,
            'mailing_list_id' => $request->mailing_list_id,
            'status' => $request->status,
            'scheduled_at' => $request->scheduled_at,
        ]);

        // If the status is 'send', trigger the send function immediately
        if ($campaign->status === 'sent') {
            $this->send($campaign->id); // Call the send function directly
        }
        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign created successfully!');
    }

    // Show form to edit campaign
    public function edit($id)
    {
        
        $page_data['campaign'] = Campaign::findOrFail($id);
        $page_data['templates'] = CampaignTemplate::all();
        $page_data['mailingLists'] = MailingList::all();
        $page_data['view_path'] = 'campaigns.edit';
        return view('backend.index',$page_data);
        //return view('campaigns.edit', compact('campaign', 'templates', 'mailingLists'));
    }

    // Update the campaign
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'campaign_template_id' => 'required|exists:campaign_templates,id',
            'mailing_list_id' => 'required|exists:mailing_lists,id',
            'status' => 'required|in:draft,scheduled,sent',
            'scheduled_at' => 'nullable|date',
        ]);

        $campaign = Campaign::findOrFail($id);
        $campaign->update([
            'name' => $request->name,
            'campaign_template_id' => $request->campaign_template_id,
            'mailing_list_id' => $request->mailing_list_id,
            'status' => $request->status,
            'scheduled_at' => $request->scheduled_at,
        ]);

        if ($campaign->status === 'sent') {
            $this->send($campaign->id); // Call the send function directly
        }

        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign updated successfully!');
    }

    // Delete the campaign
    public function destroy($id)
    {
        $campaign = Campaign::findOrFail($id);
        $campaign->delete();
        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign deleted successfully!');
    }

    // Send campaign to all subscribers in the mailing list
    public function send($id)
    {
        // Fetch the campaign with its template and mailing list
        $campaign = Campaign::with('template', 'mailingList.pages')->findOrFail($id);

        //print_r($campaign);exit;
    
        // Debugging: Print the campaign to see its contents
       
    
        // Check if the campaign has already been sent
        if ($campaign->status === 'sent') {
            //return redirect()->back()->with('error', 'This campaign has already been sent.');
        }
    
        // Loop through all the pages associated with the mailing list
        foreach ($campaign->mailingList->pages as $page) {
            // Get the email address associated with the page
            // $email = $page->item_email;
            $email = 'garg.sanjay5@gmail.com';
           
            // Ensure the email exists before dispatching the job
            if ($email) {
                // Dispatch a job for each page's email
                SendCampaignEmailJob::dispatch($campaign, $email);

            }
        }
    
        // Update the campaign status to 'sent'
        $campaign->update(['status' => 'sent']);
    
        // Return success response
        return redirect()->route('admin.campaigns.index')->with('success', 'Campaign has been sent successfully!');
    }
    

}
