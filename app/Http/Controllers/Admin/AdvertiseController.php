<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Payment\PostPlanRequest;
use App\Models\JobAdvertise;
use App\Models\Plan;
use App\Services\Common\AdvertiseService;
use App\Services\Payment\PlanService;
use Illuminate\Http\Request;
use Validator;

class AdvertiseController extends Controller
{

    private $advertiseService;

    public function __construct(AdvertiseService $advertiseService)
    {
        $this->advertiseService = $advertiseService;
    }

    public function index(Request $request)
    {
        $keyword = request('keyword') ? request('keyword') : '';
        $data = $this->advertiseService->paginate($keyword);
        return view('admin.advertise.list', compact('data', 'request'));
    }

    public function update_status(JobAdvertise $advertise)
    {
        $result = $this->advertiseService->update_status($advertise);
        if ($result) {
            return response()->json([
                'success' => [
                    'message' => "Job advertise has been Updated",
                ],
            ]);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ]);
        }
    }

    public function send_registration(JobAdvertise $advertise)
    {
        $result = $this->advertiseService->send_registration($advertise);
        if ($result->id != null) {
            return response()->json([
                'success' => [
                    'message' => "Registration e-mail has been Sent.",
                ],
            ]);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ]);
        }
    }
}
