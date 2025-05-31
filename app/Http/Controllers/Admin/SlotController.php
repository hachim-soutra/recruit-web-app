<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use App\Models\Slot;
use App\Services\Payment\PlanPackageService;
use App\Services\Payment\SlotService;
use Illuminate\Http\Request;
use Validator;

class SlotController extends Controller
{

    private $slotService;

    private $packageService;

    public function __construct(SlotService $slotService, PlanPackageService $packageService)
    {
        $this->slotService = $slotService;
        $this->packageService = $packageService;
    }

    public function index(Request $request)
    {
        $keyword = request('keyword') ? request('keyword') : '';
        $data = $this->slotService->find_all($keyword);
        return view('admin.slot.list', compact('data', 'request'));
    }

    public function show(int $id)
    {
        $slot = $this->slotService->find_by_id($id);
        return view('admin.slot.show', compact('slot'));
    }

    public function create()
    {
        $packages = $this->packageService->find_all();
        return view('admin.slot.add', compact('packages'));
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'                     => 'required',
            'slug'                      => 'required',
            'description'               => 'required',
            'good_type'                 => 'required|in:EMPLOYER',
            'good_number'               => 'required|numeric|min:1',
            'price'                     => 'required|integer|min:1',
            'packages'                  => 'required|array',
            'packages.*'                => 'exists:plan_packages,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ]);
        }
        $slot = $this->slotService->store($request->toArray());
        if ($slot->id) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Slot has been added successfully.",
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
        $slot = $this->slotService->find_by_id($id);
        $packages = $this->packageService->find_all();
        return view('admin.slot.edit', compact('slot', 'packages'));
    }

    public function update(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'title'                     => 'required',
            'slug'                      => 'required',
            'description'               => 'required',
            'status'                    => 'required|in:ACTIVE,INACTIVE',
            'good_type'                 => 'required|in:EMPLOYER',
            'good_number'               => 'required|numeric|min:1',
            'price'                     => 'required|integer|min:1',
            'packages'                  => 'required|array',
            'packages.*'                => 'exists:plan_packages,id'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ]);
        }
        $slot = $this->slotService->find_by_id($id);
        $update = $this->slotService->update($request->toArray(), $slot);
        if ($update) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    "message" => "Slot has been added successfully.",
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

    public function update_status(Slot $slot)
    {
        $result = $this->slotService->update_status($slot);
        if ($result) {
            return response()->json([
                'success' => [
                    'message' => "Slot has been Updated",
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

    public function delete(Slot $slot)
    {
        $result = $this->slotService->delete($slot);
        if ($result) {
            return response()->json([
                'success' => [
                    'message' => "Slot has been Deleted",
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
