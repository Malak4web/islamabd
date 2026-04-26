<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Project;
use App\Models\Service;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics.
     */
    public function stats()
    {
        return response()->json([
            'data' => [
                'new_contacts_count' => Contact::where('status', 'new')->count(),
                'total_projects' => Project::count(),
                'active_services' => Service::where('is_active', true)->count(),
                'media_count' => \App\Models\MediaFile::count(),
                'recent_contacts' => Contact::latest()->take(5)->get()->map(function($c) {
                    return [
                        'id' => $c->id,
                        'name' => $c->name,
                        'phone' => $c->phone,
                        'service' => $c->service,
                        'created_at' => $c->created_at,
                        'status' => $c->status,
                    ];
                }),
            ]
        ]);
    }
}
