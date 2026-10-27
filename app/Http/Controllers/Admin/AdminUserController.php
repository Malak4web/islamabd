<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AdminUserController extends Controller
{
    /**
     * Display a listing of team members / admins.
     */
    public function index(Request $request)
    {
        $admins = Admin::select('id', 'name', 'email', 'role', 'created_at', 'updated_at')
            ->orderBy('id')
            ->get();

        return response()->json([
            'data' => $admins
        ]);
    }

    /**
     * Store a newly created member in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8',
            'role' => ['required', Rule::in(['admin', 'data_entry'])],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $admin = Admin::create($validated);

        return response()->json([
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'created_at' => $admin->created_at,
            ],
            'message' => 'Team member created successfully.'
        ], 201);
    }

    /**
     * Update the specified member in storage.
     */
    public function update(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'password' => 'nullable|string|min:8',
            'role' => ['required', Rule::in(['admin', 'data_entry'])],
        ]);

        // Prevent sole admin from demoting themselves
        if ($admin->id === $request->user()->id && $validated['role'] !== 'admin') {
            $otherAdminsCount = Admin::where('role', 'admin')->where('id', '!=', $admin->id)->count();
            if ($otherAdminsCount === 0) {
                return response()->json([
                    'message' => 'Cannot remove administrator role from the only administrator.'
                ], 422);
            }
        }

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $admin->update($validated);

        return response()->json([
            'data' => [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'created_at' => $admin->created_at,
            ],
            'message' => 'Team member updated successfully.'
        ]);
    }

    /**
     * Remove the specified member from storage.
     */
    public function destroy(Request $request, string $id)
    {
        $admin = Admin::findOrFail($id);

        if ($admin->id === $request->user()->id) {
            return response()->json([
                'message' => 'You cannot delete your own account.'
            ], 422);
        }

        if ($admin->role === 'admin' && Admin::where('role', 'admin')->count() <= 1) {
            return response()->json([
                'message' => 'Cannot delete the only remaining administrator.'
            ], 422);
        }

        $admin->delete();

        return response()->json([
            'message' => 'Team member deleted successfully.'
        ], 200);
    }
}
