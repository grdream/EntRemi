<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserSmtpSetting;

class ManageSmtp extends Component
{
    public $host = '';
    public $port = 587;
    public $username = '';
    public $password = '';
    public $encryption = 'tls';
    public $from_address = '';
    public $from_name = '';
    public $is_active = true;

    public function mount()
    {
        $setting = auth()->user()->smtpSetting;
        if ($setting) {
            $this->host = $setting->host;
            $this->port = $setting->port;
            $this->username = $setting->username;
            $this->encryption = $setting->encryption;
            $this->from_address = $setting->from_address;
            $this->from_name = $setting->from_name;
            $this->is_active = $setting->is_active;
        }
    }

    protected $rules = [
        'host' => 'required|string',
        'port' => 'required|integer',
        'username' => 'required|string',
        'encryption' => 'required|in:tls,ssl,none',
        'from_address' => 'required|email',
        'from_name' => 'required|string',
        'is_active' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        $setting = auth()->user()->smtpSetting ?? new UserSmtpSetting(['user_id' => auth()->id()]);
        
        $setting->host = $this->host;
        $setting->port = $this->port;
        $setting->username = $this->username;
        $setting->encryption = $this->encryption;
        $setting->from_address = $this->from_address;
        $setting->from_name = $this->from_name;
        $setting->is_active = $this->is_active;

        if ($this->password) {
            $setting->setPassword($this->password);
        } elseif (!$setting->exists) {
            $this->addError('password', 'Password is required for new configurations.');
            return;
        }

        $setting->save();

        session()->flash('smtp_status', 'SMTP configuration saved securely.');
    }

    public function render()
    {
        return view('livewire.manage-smtp');
    }
}
