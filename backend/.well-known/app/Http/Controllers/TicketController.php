<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TicketController extends Controller
{
    /**
     * Display a list of all tickets.
     */
    public function index()
    {
        $tickets = Ticket::latest()->paginate(10);
        return view('backend.admin.tickets.index', compact('tickets'));
    }

    /**
     * Show a single ticket with details.
     */
    public function show(Ticket $ticket)
    {
        return view('backend.admin.tickets.show', compact('ticket'));
    }

    /**
     * Update ticket status, add comments and upload screenshot.
     */
    public function update(Request $request, Ticket $ticket)
    {
        $request->validate([
            'status'      => 'required|in:Open,In Progress,Closed',
            'admin_comment' => 'nullable|string',
            'screenshot'  => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['status', 'admin_comment']);

        // Handle Screenshot Upload
        if ($request->hasFile('screenshot')) {
            if ($ticket->screenshot) {
                Storage::delete('public/' . $ticket->screenshot);
            }
            $path = $request->file('screenshot')->store('screenshots', 'public');
            $data['screenshot'] = $path;
        }

        $ticket->update($data);

        return redirect()->route('admin.tickets.index')->with('success', 'Ticket updated successfully.');
    }
}
