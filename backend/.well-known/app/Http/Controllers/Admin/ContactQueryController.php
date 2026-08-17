<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ContactQuery;
use Carbon\Carbon;
class ContactQueryController extends Controller
{
    public function index(Request $request)
    {
        $query = ContactQuery::query();
        if ($request->filled('from') && $request->filled('to')) {
            $from = Carbon::parse($request->from)->startOfDay();
            $to   = Carbon::parse($request->to)->endOfDay();
            $query->whereBetween('created_at', [$from, $to]);
        }
        $page_data['queries']    = $query->latest()->paginate(20);
        $page_data['view_path']  = 'contact_queries.index';
        return view('backend.index', $page_data);
    }
    public function edit($id)
    {
        $contactQuery = ContactQuery::findOrFail($id);
        $page_data['contactQuery'] = $contactQuery;
        $page_data['view_path']    = 'contact_queries.edit';
        return view('backend.index', $page_data);
    }
    public function update(Request $request, $id)
    {
        $contactQuery = ContactQuery::findOrFail($id);
        $validated = $request->validate([
            'name'  => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'required|email',
            'city'  => 'required|string|max:100',
            'query' => 'required|string',
        ]);
        $contactQuery->update($validated);
        return redirect()->route('admin.contact.queries')
                         ->with('success', 'Query updated successfully!');
    }
    public function destroy($id)
    {
        ContactQuery::findOrFail($id)->delete();
        return redirect()->route('admin.contact.queries')
                         ->with('success', 'Query deleted successfully!');
    }
}