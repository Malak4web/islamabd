<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    /**
     * Display a listing of the contacts.
     */
    public function index(Request $request)
    {
        $query = Contact::query()->latest();

        if ($request->has('status') && in_array($request->status, ['new', 'read', 'replied'])) {
            $query->byStatus($request->status);
        }

        $paginator = $query->paginate(15);

        return response()->json([
            'data' => $paginator->items(),
            'meta' => [
                'total' => $paginator->total(),
                'per_page' => $paginator->perPage(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
            ]
        ]);
    }


    /**
     * Display the specified contact.
     */
    public function show(string $id)
    {
        $contact = Contact::findOrFail($id);

        return response()->json([
            'data' => $contact
        ]);
    }

    /**
     * Mark the contact as read.
     */
    public function markRead(string $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => 'read']);

        return response()->json([
            'data' => $contact,
            'message' => 'Contact marked as read.'
        ]);
    }

    /**
     * Mark the contact as replied.
     */
    public function markReplied(string $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->update(['status' => 'replied']);

        return response()->json([
            'data' => $contact,
            'message' => 'Contact marked as replied.'
        ]);
    }

    /**
     * Remove the specified contact from storage.
     */
    public function destroy(string $id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return response()->json(null, 204);
    }

    /**
     * Bulk remove contacts from storage.
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:contacts,id'
        ]);

        Contact::whereIn('id', $request->ids)->delete();

        return response()->json([
            'message' => 'Contacts deleted successfully.'
        ]);
    }
}
