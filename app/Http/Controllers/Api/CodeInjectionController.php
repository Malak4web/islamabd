<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CodeInjection;
use Illuminate\Http\Request;

class CodeInjectionController extends Controller
{
    /**
     * Get active code injections filtered by page and grouped by location.
     */
    public function index(Request $request)
    {
        $slug = $request->query('page', 'home');

        $injections = CodeInjection::where('is_active', true)
            ->where(function ($q) use ($slug) {
                $q->whereNull('pages')
                  ->orWhere('pages', 'LIKE', "%\"{$slug}\"%"); // SQLite compatible JSON search
            })
            ->get();

        return response()->json([
            'data' => [
                'head'       => $injections->where('location', 'head')->values(),
                'body_start' => $injections->where('location', 'body_start')->values(),
                'body_end'   => $injections->where('location', 'body_end')->values(),
            ]
        ]);
    }
}
