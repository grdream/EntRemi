<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\UserSmsSetting;

class ManageSms extends Component
{
    public $gateway_url = '';
    public $api_key = '';
    public $sender_id = '';
    public $extra_params_json = '';
    public $is_active = true;

    public function mount()
    {
        $setting = auth()->user()->smsSetting;
        if ($setting) {
            $this->gateway_url = $setting->gateway_url;
            $this->sender_id = $setting->sender_id;
            $this->extra_params_json = $setting->extra_params ? json_encode($setting->extra_params, JSON_PRETTY_PRINT) : '';
            $this->is_active = $setting->is_active;
        }
    }

    protected $rules = [
        'gateway_url' => 'required|url',
        'sender_id' => 'nullable|string',
        'is_active' => 'boolean',
    ];

    public function save()
    {
        $this->validate();

        $extraParams = [];
        if (!empty(trim($this->extra_params_json))) {
            $parsed = json_decode($this->extra_params_json, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                $this->addError('extra_params_json', 'Invalid JSON format.');
                return;
            }
            $extraParams = $parsed;
        }

        $setting = auth()->user()->smsSetting ?? new UserSmsSetting(['user_id' => auth()->id()]);
        
        $setting->gateway_url = $this->gateway_url;
        $setting->sender_id = $this->sender_id;
        $setting->extra_params = $extraParams;
        $setting->is_active = $this->is_active;

        if ($this->api_key) {
            $setting->setApiKey($this->api_key);
        }

        $setting->save();

        session()->flash('sms_status', 'SMS Gateway configuration saved securely.');
    }

    public function render()
    {
        return view('livewire.manage-sms');
    }
}
