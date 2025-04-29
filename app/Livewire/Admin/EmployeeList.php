<?php

namespace App\Livewire\Admin;

use App\Models\Employee;
use Livewire\Component;
use Livewire\WithPagination;

class EmployeeList extends Component
{
    use WithPagination;

    public $search = '';
    public $first_name;
    public $last_name;
    public $email;
    public $phone_number;
    public $address;
    public $gender;
    public $editingEmployeeId = null;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255|unique:employees,email',
        'phone_number' => 'required|string|max:20',
        'address' => 'required|string',
        'gender' => 'required|in:male,female',
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetValidation();
        $this->reset(['first_name', 'last_name', 'email', 'phone_number', 'address', 'gender', 'editingEmployeeId']);
    }

    public function edit(Employee $employee)
    {
        $this->editingEmployeeId = $employee->id;
        $this->first_name = $employee->first_name;
        $this->last_name = $employee->last_name;
        $this->email = $employee->email;
        $this->phone_number = $employee->phone_number;
        $this->address = $employee->address;
        $this->gender = $employee->gender;
    }

    public function save()
    {
        $this->validate();

        if ($this->editingEmployeeId) {
            $employee = Employee::find($this->editingEmployeeId);
            $employee->update([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'address' => $this->address,
                'gender' => $this->gender,
            ]);
            session()->flash('message', 'Employee updated successfully.');
        } else {
            Employee::create([
                'first_name' => $this->first_name,
                'last_name' => $this->last_name,
                'email' => $this->email,
                'phone_number' => $this->phone_number,
                'address' => $this->address,
                'gender' => $this->gender,
            ]);
            session()->flash('message', 'Employee created successfully.');
        }

        $this->reset(['first_name', 'last_name', 'email', 'phone_number', 'address', 'gender', 'editingEmployeeId']);
    }

    public function delete(Employee $employee)
    {
        $employee->delete();
        session()->flash('message', 'Employee deleted successfully.');
    }

    public function render()
    {
        return view('livewire.admin.employee-list', [
            'employees' => Employee::where('first_name', 'like', '%' . $this->search . '%')
                ->orWhere('last_name', 'like', '%' . $this->search . '%')
                ->orWhere('email', 'like', '%' . $this->search . '%')
                ->paginate(10),
        ]);
    }
}
