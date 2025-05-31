<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Services\EmployerService;
use App\Services\Payment\PlanService;
use Illuminate\Http\Request;
use Validator;

class PlanController extends Controller
{
    public function __construct(private PlanService $planService, private EmployerService $employerService)
    {
        $this->planService = $planService;
        $this->employerService = $employerService;
    }

    public function index(Request $request)
    {
        $data = $this->planService->find_all(request('keyword'));
        return view('admin.plan.list', compact('data', 'request'));
    }

    public function show(int $id)
    {
        $plan = $this->planService->find_by_id($id);
        return view('admin.plan.show', compact('plan'));
    }

    public function create()
    {
        $employers = $this->employerService->getEmployersUnsubscribed();
        return view('admin.plan.add', compact('employers'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'                     => 'required',
            'slug'                      => 'required',
            'description'               => 'required',
            'plan_for'                  => 'required|in:EMPLOYER,COACH',
            'plan_type'                 => 'required|in:FREE,SITE,SALE',
            'number_of_month'           => 'required|numeric|min:1|max:12',
            'job_number'                => 'required|numeric|min:1',
            'price'                     => 'required|integer|min:0',
        ]);

        if ($validator->fails()) {
            return $this->throwValidation($validator);
        }

        $plan = $this->planService->store($request->toArray());

        if ($plan->id) {
            if (count($request->employers) > 0) {
                $this->planService->assignEmployersToPlan($plan, $request->employers); // this is job for create payement link
            }
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Plan has been added successfully.",
                ],
            ]);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Sorry a problem has occurred.",
                ],
            ]);
        }
    }

    public function edit(int $id)
    {
        $plan = $this->planService->find_by_id($id);
        return view('admin.plan.edit', compact('plan'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title'                     => 'required',
            'slug'                      => 'required',
            'description'               => 'required',
            'status'                    => 'required|in:ACTIVE,INACTIVE',
            'plan_for'                  => 'required|in:EMPLOYER,COACH',
            'plan_type'                 => 'required|in:SITE,FREE,SALE',
            'job_number'                => 'required|numeric|min:1',
            'price'                     => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return $this->throwValidation($validator);
        }

        $plan = $this->planService->find_by_id($id);
        $update = $this->planService->update($request->toArray(), $plan);
        if ($update) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Plan has been added successfully.",
                ],
            ]);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    "message" => "Sorry a problem has occurred.",
                ],
            ]);
        }
    }

    public function update_status(Plan $plan)
    {
        $result = $this->planService->update_status($plan);
        if ($result) {
            return response()->json([
                'success' => [
                    'message' => "Plan has been Updated",
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

    public function delete(Plan $plan)
    {
        $result = $this->planService->delete($plan);
        if ($result) {
            return response()->json([
                'success' => [
                    'message' => "Plan has been Deleted",
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

    /*
    * INFO: Show assign employers blade
    */
    public function assign(int $id)
    {
        $plan = $this->planService->find_by_id($id);
        $employers = $this->employerService->getEmployersUnsubscribed();
        return view('admin.plan.assign', compact('plan', 'employers'));
    }

    /*
    * INFO: Create payement link for employers and save
    */
    public function assignStore(int $id, Request $request)
    {
        $request->validate([
            "employers" => "required|array|min:1"
        ]);
        $this->planService->assignEmployersToPlan($this->planService->find_by_id($id), $request->employers); // this is job for create payement link
        return redirect()->route('admin.plan.list');
    }

    private function throwValidation($validator)
    {
        return response()->json([
            'data' => [
                "status"  => 'validation_error',
                "message" => $validator->errors(),
            ],
        ]);
    }
}
