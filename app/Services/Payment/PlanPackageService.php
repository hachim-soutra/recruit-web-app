<?php

namespace App\Services\Payment;

use App\Enum\Payments\PlanStatusEnum;
use App\Models\Plan;
use App\Models\PlanPackage;

class PlanPackageService
{
    protected $packageModel;

    public function __construct(PlanPackage $package)
    {
        $this->packageModel = $package;
    }

    public function find_all()
    {
        return $this->packageModel::with('plan')->get();
    }

    public function find_by_id($id): ?PlanPackage
    {
        return PlanPackage::with('plan')->findOrFail($id);
    }
}
