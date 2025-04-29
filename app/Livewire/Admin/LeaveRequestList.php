<?php

namespace App\Livewire\Admin;

use App\Models\LeaveRequest;
use Livewire\Component;
use Livewire\WithPagination;

class LeaveRequestList extends Component
{
    use WithPagination;

    public $search = '';
    public $status = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatus()
    {
        $this->resetPage();
    }

    public function approve(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update(['status' => 'approved']);
        session()->flash('message', 'Leave request approved successfully.');
    }

    public function reject(LeaveRequest $leaveRequest)
    {
        $leaveRequest->update(['status' => 'rejected']);
        session()->flash('message', 'Leave request rejected successfully.');
    }

    public function render()
    {
        $query = LeaveRequest::with('employee')
            ->when($this->search, function ($query) {
                $query->whereHas('employee', function ($q) {
                    $q->where('first_name', 'like', '%' . $this->search . '%')
                        ->orWhere('last_name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->status, function ($query) {
                $query->where('status', $this->status);
            });

        return view('livewire.admin.leave-request-list', [
            'leaveRequests' => $query->latest()->paginate(10),
        ]);
    }
}
