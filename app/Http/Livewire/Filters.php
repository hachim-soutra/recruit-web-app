<?php

namespace App\Http\Livewire;

use Livewire\Component;

class Filters extends Component
{
    public $keyword;
    public $roleFilter;
    public $locationFilter;
    public $employerFilter;
    public $sectorFilter;

    public $roles;
    public $locations;
    public $employers;
    public $sectors;
    public $jobs;

    public function mount($jobs, $keyword,  $sectorFilter, $roleFilter, $employerFilter, $locationFilter)
    {
        $this->roles = $jobs->groupBy('preferred_job_type')->toBase();
        $this->locations = $jobs->groupBy('job_location')->toBase();
        $this->employers = $jobs->groupBy('employer.employer.company_name')->toBase();
        $this->sectors = $jobs->groupBy('functional_area')->toBase();

        $this->roleFilter = $roleFilter;
        $this->locationFilter = $locationFilter;
        $this->employerFilter = $employerFilter;
        $this->sectorFilter = $sectorFilter;
    }

    public function render()
    {
        return view('livewire.filters');
    }
}
