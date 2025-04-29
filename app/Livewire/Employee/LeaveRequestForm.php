<?php

namespace App\Livewire\Employee;

use App\Models\LeaveRequest;
use Livewire\Component;
use Carbon\Carbon;

class LeaveRequestForm extends Component
{
    public $reason = '';
    public $start_date = '';
    public $end_date = '';
    public $employee_id;

    protected $rules = [
        'reason' => 'required|string|min:10',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after_or_equal:start_date',
    ];

    public function mount($employeeId)
    {
        $this->employee_id = $employeeId;
    }

    public function submit()
    {
        $this->validate();

        // Check if employee has already taken leave in the same month
        $startDate = Carbon::parse($this->start_date);
        $endDate = Carbon::parse($this->end_date);

        $existingLeave = LeaveRequest::where('employee_id', $this->employee_id)
            ->whereMonth('start_date', $startDate->month)
            ->whereYear('start_date', $startDate->year)
            ->exists();

        if ($existingLeave) {
            $this->addError('start_date', 'You have already taken leave in this month.');
            return;
        }

        // Check if employee has exceeded annual leave limit
        $currentYear = Carbon::now()->year;
        $totalLeaveDays = LeaveRequest::where('employee_id', $this->employee_id)
            ->whereYear('start_date', $currentYear)
            ->where('status', 'approved')
            ->get()
            ->sum(function ($request) {
                return Carbon::parse($request->start_date)->diffInDays(Carbon::parse($request->end_date)) + 1;
            });

        $requestedDays = $startDate->diffInDays($endDate) + 1;
        if ($totalLeaveDays + $requestedDays > 12) {
            $this->addError('end_date', 'You have exceeded the annual leave limit of 12 days.');
            return;
        }

        LeaveRequest::create([
            'employee_id' => $this->employee_id,
            'reason' => $this->reason,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'status' => 'pending',
        ]);

        $this->reset(['reason', 'start_date', 'end_date']);
        session()->flash('message', 'Leave request submitted successfully.');
    }

    public function render()
    {
        return view('livewire.employee.leave-request-form');
    }
}
