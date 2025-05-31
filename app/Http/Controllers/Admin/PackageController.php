<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use App\Models\Setting;
use Illuminate\Http\Request;
use Validator;
use Stripe\StripeClient;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Stripe;

class PackageController extends Controller
{
    public function index($type = 'active', Request $request = null)
    {
        //$keyword = $request->input('keyword', null);
		$keyword = '';
		if($request == null || $request == '') {
			
			$request = (object)[];
			$request->keyword = $keyword = trim(request('keyword'));
			
		} else { $keyword = trim($request->input('keyword', null)); }
		
        $data = Package::orderBy('vatprice', 'ASC')
            ->where(function ($q) use ($type) {
                if ($type === 'active') {
                    $q->where('status', 1);
                }
                if ($type === 'pending') {
                    $q->where('status', 0);
                }
            })
            ->where(function ($q) use ($keyword) {
                if ($keyword) {
                    $q->orWhere('title', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('price', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('package_for', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('plan_interval', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('number_of_job_post', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->paginate(15);
        return view('admin.package.list', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.package.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'              => 'required|string|max:250',
            'price'              => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'details'            => 'required|string',
            'plan_interval'      => 'required',
            'package_for'        => 'required',
            'number_of_job_post' => 'required_if:package_for,employer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $data = Setting::where('id', 1)->first();
        //$stripe = new StripeClient($data->secret_key);
        $stripe = new \Stripe\StripeClient(
            $data->secret_key
        );
        // Stripe\Stripe::setApiKey($data->secret_key);
        $price = floatval($request->input('price'));
        $vatprice = floatval($price + (($price*23)/100));
        try {               
            $plan = $stripe->plans->create([
                'amount'         => floatval($vatprice * 100),  
                'currency'       => 'eur',
                'interval'       => $request->input('plan_interval'),                   
                //'interval'       => $plan->plan_interval,                   
                "product" => [ 
                    "name" => $request->input('title'),
                ],
                'interval_count' => 1
              ]);
        }catch(Exception $e) {   
            $api_error = $e->getMessage();
            return response()->json([
                'data' => [
                    "status" => 'error',
                    "message"    => 'Opps error! Customer Plan Create Failed',
                    "error"      => $e->getMessage(),
                ],
            ], 200);

        } 
        if(empty($api_error) && $plan){
            $package                     = new Package;
            $package->title              = $request->input('title');
            $package->plan_key           = $plan->id;
            $package->price              = $request->input('price');
            $package->vat                   = 23;
            $package->vatprice              = $vatprice;
            $package->plan_interval      = $request->input('plan_interval');
            if($request->input('package_for') == 'employer'){
                $package->number_of_job_post = $request->input('number_of_job_post');
            }
            $package->package_for        = $request->input('package_for');
            $package->details            = $request->input('details');
            $package->status             = 1;

            if ($package->save()) {
                return response()->json([
                    'data' => [
                        "status" => 'success',
                        "message" => "Added a new Package.",
                    ],
                ], 200);
            } else {
                return response()->json([
                    'data' => [
                        "status" => 'error',
                        "message" => "System error please try after sometime",
                    ],
                ], 200);
            }
        } else {
            return response()->json([
                'data' => [
                    "status" => 'error',
                    "message" => "System error please try after sometime",
                ],
            ], 200);
        }
    }

    public function edit($id)
    {
        $package = Package::where('id', $id)
            ->first();
        return view('admin.package.edit', compact('package'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'              => 'required|string|max:250',
            'price'              => 'required|regex:/^\d+(\.\d{1,2})?$/',
            'details'            => 'required|string',
            'plan_interval'      => 'required',
            'package_for'        => 'required',
            'number_of_job_post' => 'required_if:package_for,employer',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $price = floatval($request->input('price'));
        $vatprice = floatval($price + (($price*23)/100));

        $package = Package::where('id', $id)
            ->first();
        $package->title                 = $request->input('title');
        $package->price                 = $request->input('price');
        $package->vat                   = 23;
        $package->vatprice              = $vatprice;
        $package->plan_interval = $request->input('plan_interval');
        if($request->input('package_for') == 'employer'){
            $package->number_of_job_post = $request->input('number_of_job_post');
        }
        $package->package_for        = $request->input('package_for');
        $package->details = $request->input('details');
        $package->status = 1;
        if ($package->save()) {
            return response()->json([
                'data' => [
                    "status" => 'success',
                    "message" => "Updated a package successfully done.",
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status" => 'error',
                    "message" => "System error please try after sometime",
                ],
            ], 200);
        }
    }

    public function archive($id)
    {
        $res = Package::where('id', $id)
            ->update([
                'status' => 0,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Package has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ], 200);
        }

    }

    public function restore($id)
    {
        $res = Package::where('id', $id)
            ->update([
                'status' => 1,
            ]);
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Package has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ], 200);
        }
    }

    public function destroy($id)
    {
        $package = Package::findOrFail($id);

        $res = $package->delete();
        if ($res) {
            return response()->json([
                'success' => [
                    'message' => "Record has been Removed",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Record not Found",
                ],
            ], 200);
        }
    }
}
