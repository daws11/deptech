<?php

namespace App\Http\Controllers;

use App\Models\Leave;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LeaveController extends Controller
{
    public function index()
    {
        if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
            $leaves = Leave::with('employee')->latest()->paginate(10);
        } else {
            $leaves = Auth::user()->leaves()->latest()->paginate(10);
        }
        return view('leaves.index', compact('leaves'));
    }

    public function create()
    {
        if (Auth::user()->isAdmin() || Auth::user()->isSuperAdmin()) {
            $employees = User::where('role', 'user')->get();
        } else {
            $employees = collect([Auth::user()]);
        }
        return view('leaves.create', compact('employees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'employee_id' => 'required|exists:users,id',
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $duration = $start->diffInDays($end) + 1;

        $leave = Leave::create([
            'employee_id' => $validated['employee_id'],
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration_days' => $duration,
            'reason' => $validated['reason'],
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return redirect()->route('leaves.show', $leave)
            ->with('success', 'Leave request created successfully.');
    }

    public function show(Leave $leave)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin() && $leave->employee_id !== Auth::id()) {
            abort(403);
        }
        return view('leaves.show', compact('leave'));
    }

    public function edit(Leave $leave)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin() && $leave->employee_id !== Auth::id()) {
            abort(403);
        }
        return view('leaves.edit', compact('leave'));
    }

    public function update(Request $request, Leave $leave)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin() && $leave->employee_id !== Auth::id()) {
            abort(403);
        }

        $validated = $request->validate([
            'leave_type' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'required|string',
        ]);

        $start = \Carbon\Carbon::parse($validated['start_date']);
        $end = \Carbon\Carbon::parse($validated['end_date']);
        $duration = $start->diffInDays($end) + 1;

        $leave->update([
            'leave_type' => $validated['leave_type'],
            'start_date' => $validated['start_date'],
            'end_date' => $validated['end_date'],
            'duration_days' => $duration,
            'reason' => $validated['reason'],
        ]);

        return redirect()->route('leaves.show', $leave)
            ->with('success', 'Leave request updated successfully.');
    }

    public function destroy(Leave $leave)
    {
        if (!Auth::user()->isAdmin() && !Auth::user()->isSuperAdmin() && $leave->employee_id !== Auth::id()) {
            abort(403);
        }

        $leave->delete();

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request deleted successfully.');
    }
} 