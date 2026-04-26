<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;

class ContactController extends Controller
{
    /**
     * Store a newly created contact message in storage.
     */
    public function store(StoreContactRequest $request)
    {
        $contact = Contact::create($request->validated() + ['status' => 'new']);

        return response()->json([
            'message' => 'Thank you! We will contact you shortly.',
            'data' => [
                'id' => $contact->id,
                'name' => $contact->name,
                'created_at' => $contact->created_at
            ]
        ], 201);
    }
}
