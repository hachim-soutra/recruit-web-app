<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Coupon;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Validator;
use Stripe;
use Stripe\StripeClient;
use App\Models\Setting;
use App\Models\CouponUser;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Mail;
use App\Mail\CouponShareMail;

class CouponController extends Controller
{
    public function index($type = 'active', Request $request = null)
    {

        //$keyword = $request->input('keyword', null);
		$keyword = '';
		if($request == null || $request == '') {

			$request = (object)[];
			$request->keyword = $keyword = trim(request('keyword'));

		} else { $keyword = trim($request->input('keyword', null)); }

        $data = Coupon::orderBy('updated_at', 'DESC')
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
                    $q->orWhere('coupon_title', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('coupon_for', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('code', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('coupon_amount', 'LIKE', '%' . $keyword . '%');
                    $q->orWhere('total_usage', 'LIKE', '%' . $keyword . '%');
                }
            })
            ->paginate(15);
        return view('admin.coupon.list', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.coupon.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_title'      => 'required|string|max:250',
            'coupon_for'        => 'required|string',
            'coupon_amount'     => 'required|digits_between:2,5',
            'coupon_start_date' => 'required|date|after:'.date('Y-m-d'),
            'coupon_expiry_date'=> 'required|date|after:coupon_start_date',
            'description'       => 'required',
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
    }

    public function show(Request $request, $id)
    {
        $coupon = Coupon::where('id', $id)
            ->first();
        return view('admin.coupon.show', compact('coupon'));
    }

    public function edit($id)
    {
        $coupon = Coupon::where('id', $id)
            ->first();
        return view('admin.coupon.edit', compact('coupon'));
    }

    public function update($id, Request $request)
    {
        $validator = Validator::make($request->all(), [
            'coupon_title'      => 'required|string|max:250',
            'coupon_for'        => 'required|string',
            'coupon_amount'     => 'required|digits_between:2,5',
            'coupon_start_date' => 'required|date|after:'.date('Y-m-d'),
            'coupon_expiry_date'=> 'required|date|after:coupon_start_date',
            'description'       => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status" => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }
        $coupon = Coupon::where('id', $id)
            ->first();
        $coupon->coupon_title       = $request->input('coupon_title');
        $coupon->coupon_for         = $request->input('coupon_for');
        $coupon->coupon_amount      = $request->input('coupon_amount');
        $coupon->coupon_limit       = $request->input('coupon_limit');
        $coupon->coupon_start_date  = $request->input('coupon_start_date');
        $coupon->coupon_expiry_date = $request->input('coupon_expiry_date');
        $coupon->description        = $request->input('description');

        if ($coupon->save()) {
            return response()->json([
                'data' => [
                    "status" => 'success',
                    "message" => "Updated a coupon successfully done.",
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

    public function destroy($id)
    {
        $res = Coupon::where('id', $id)
            ->update([
                'status' => 0,
            ]);
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

    public function share(Request $request, $id)
    {
        $coupon = Coupon::where('id', $id)->first();

        $employers = User::select('id', 'name', 'email', 'user_type')->orderBy('updated_at', 'DESC')
            ->where('user_type', 'employer')
            ->where('status', 1)
            ->where('email_verified', 1)
            ->where('mobile_verified', 1)
            ->whereNotIn('id', CouponUser::where('coupon_id', $id)->pluck('user_id'))
           // ->where('verified', 1)
           // ->where('is_complete', 1)
            ->get();
        $coaches = User::select('id', 'name', 'email', 'user_type')->orderBy('updated_at', 'DESC')
            ->where('user_type', 'coach')
            ->where('status', 1)
            ->where('email_verified', 1)
            ->where('mobile_verified', 1)
            ->whereNotIn('id', CouponUser::where('coupon_id', $id)->pluck('user_id'))
           // ->where('verified', 1)
           // ->where('is_complete', 1)
            ->get();
        return view('admin.coupon.share', compact('employers', 'coaches', 'coupon'));
    }

    public function shareCoupon(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [

            'employer'      => 'required_without:coach|array|min:1|max:5',
            'coach'         => 'required_without:employer|array|min:1|max:5',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }else{
        $coupon = Coupon::where('id', $id)->first();


        if(!empty($request->input('employer')) && count($request->input('employer'))>0){

            foreach ($request->input('employer') as $key => $value) {
                $coup_user                     = new CouponUser;
                $coup_user->user_id            = $value;
                $coup_user->coupon_id          = $id;
                $coup_user->save();

                $user = User::select('id', 'name', 'email')->where('id', $value)->first();

                $data = array();
                $data['user_name']          = $user->name;
                $data['coupon_title']       = $coupon->coupon_title;
                $data['code']               = $coupon->code;
                $data['coupon_limit']       = $coupon->coupon_limit;
                $data['description']        = $coupon->description;
                $data['coupon_amount']      = $coupon->coupon_amount;
                $data['coupon_start_date']  = $coupon->coupon_start_date;
                $data['coupon_expiry_date'] = $coupon->coupon_expiry_date;

                Mail::to($user->email)->send(new CouponShareMail($data));
            }
        }

        if(!empty($request->input('coach')) && count($request->input('coach'))>0){
            foreach ($request->input('coach') as $key => $value) {
                $coup_user                     = new CouponUser;
                $coup_user->user_id            = Auth::user()->id;
                $coup_user->coupon_id          = $id;
                $coup_user->save();

                $user = User::select('id', 'name', 'email')->where('id', $value)->first();

                $data = array();
                $data['user_name']          = $user->name;
                $data['coupon_title']       = $coupon->coupon_title;
                $data['code']               = $coupon->code;
                $data['coupon_limit']       = $coupon->coupon_limit;
                $data['description']        = $coupon->description;
                $data['coupon_amount']      = $coupon->coupon_amount;
                $data['coupon_start_date']  = $coupon->coupon_start_date;
                $data['coupon_expiry_date'] = $coupon->coupon_expiry_date;

                Mail::to($user->email)->send(new CouponShareMail($data));
            }
        }


            return response()->json([
                'data' => [
                    "status" => 'success',
                    "message" => "Added a new Coupon.",
                ],
            ], 200);
        }

    }

    public function shareList(Request $request, $id)
    {
        $data = Coupon::where('id', $id)
            ->with('coupon_user')
            ->first();
        return view('admin.coupon.share_list', compact('data', 'request'));
    }
}
