<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class Profile extends Component
{
    public $first_name;
    public $last_name;
    public $email;
    public $date_of_birth;
    public $gender;
    public $current_password;
    public $new_password;
    public $new_password_confirmation;

    protected $rules = [
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'date_of_birth' => 'required|date',
        'gender' => 'required|in:male,female',
    ];

    public function mount()
    {
        $admin = Auth::guard('admin')->user();
        $this->first_name = $admin->first_name;
        $this->last_name = $admin->last_name;
        $this->email = $admin->email;
        $this->date_of_birth = $admin->date_of_birth->format('Y-m-d');
        $this->gender = $admin->gender;
    }

    public function updateProfile()
    {
        $this->validate();

        $admin = Auth::guard('admin')->user();
        $admin->update([
            'first_name' => $this->first_name,
            'last_name' => $this->last_name,
            'email' => $this->email,
            'date_of_birth' => $this->date_of_birth,
            'gender' => $this->gender,
        ]);

        session()->flash('message', 'Profile updated successfully.');
    }

    public function updatePassword()
    {
        $this->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8|confirmed',
        ]);

        $admin = Auth::guard('admin')->user();

        if (!Hash::check($this->current_password, $admin->password)) {
            $this->addError('current_password', 'The current password is incorrect.');
            return;
        }

        $admin->update([
            'password' => Hash::make($this->new_password),
        ]);

        $this->reset(['current_password', 'new_password', 'new_password_confirmation']);
        session()->flash('message', 'Password updated successfully.');
    }

    public function render()
    {
        return view('livewire.admin.profile');
    }
}
