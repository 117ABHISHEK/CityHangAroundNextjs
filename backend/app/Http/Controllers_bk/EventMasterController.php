<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventMaster;

class EventMasterController extends Controller
{
    // Show All Events
    public function index(Request $request) {
        $query = EventMaster::query();
    
        // 🔹 Search by Event Name
        if ($request->filled('search')) {
            $query->where('event_name', 'like', '%' . $request->search . '%');
        }
    
        // 🔹 Filter by Start Date & End Date
        if ($request->filled('start_date')) {
            $query->whereDate('created_at', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('created_at', '<=', $request->end_date);
        }
    
        // 🔹 Get filtered results with pagination (10 per page)
        $events = $query->orderBy('created_at', 'desc')->paginate(10);
    
        // 🔹 Pass data to view
        $page_data['events'] = $events;
        $page_data['view_path'] = 'event_master.index';
    
        return view('backend.index', $page_data);
    }
    

    // Show Create Form
    public function create() {

        $page_data['view_path'] = 'event_master.create';
        return view('backend.index', $page_data);
        //return view('event_master.create');
    }

    // Store New Event
    public function store(Request $request) {
        $request->validate([
            'event_name' => 'required|unique:event_masters|string|max:255',
            'score' => 'required|integer|min:1',
        ]);

        EventMaster::create($request->all());

        return redirect()->route('admin.event.index')->with('success', 'Event created successfully!');
    }

    // Show Edit Form
    public function edit($id) {
        $event = EventMaster::findOrFail($id);

        $page_data['event'] =$event;
        $page_data['view_path'] = 'event_master.edit';
        return view('backend.index', $page_data);
        //return view('event_master.edit', compact('event'));
    }

    // Update Event
    public function update(Request $request, $id) {
        $request->validate([
            'event_name' => 'required|string|max:255|unique:event_masters,event_name,' . $id,
            'score' => 'required|integer|min:1',
        ]);

        $event = EventMaster::findOrFail($id);
        $event->update($request->all());

        return redirect()->route('admin.event.index')->with('success', 'Event updated successfully!');
    }

    // Delete Event
    public function destroy($id) {
        $event = EventMaster::findOrFail($id);
        $event->delete();

        return redirect()->route('admin.event.index')->with('success', 'Event deleted successfully!');
    }
}
