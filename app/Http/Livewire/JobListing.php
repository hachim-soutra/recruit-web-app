<?php

namespace App\Http\Livewire;

use App\Models\JobPost;
use Livewire\Component;

class JobListing extends Component
{

    public $data;

    public function mount($data)
    {
        $this->data = $data;
    }

    public function render()
    {
        return view('livewire.job-listing');
    }
}
