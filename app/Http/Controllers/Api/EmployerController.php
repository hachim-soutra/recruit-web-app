<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Mail\VerifyUser;
use App\Models\CompanyBookmark;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Validator;

class EmployerController extends Controller
{
    public function profileUpdate(Request $request)
    {
        if (Auth::check()) {

            $validator = Validator::make($request->all(), [
                'first_name'          => 'required',
                'last_name'           => 'required',
                'email'               => 'required',
                'mobile'              => 'required',
                'address'             => 'required',
                'city'                => 'nullable',
                'state'               => 'nullable',
                'country'             => 'nullable',
                'zip'                 => 'nullable',
                'industry_id'         => 'nullable',
                'company_ceo'         => 'nullable',
                'number_of_employees' => 'nullable',
                'established_in'      => 'nullable',
                'fax'                 => 'nullable',
                'facebook_address'    => 'nullable',
                'twitter'             => 'nullable',
                'ownership_type'      => 'nullable',
                'phone_number'        => 'nullable',
                'company_details'     => 'required',
                'linkedin_link'       => 'nullable',
                'website_link'        => 'required',
                'company_name'        => 'required',
                'tag_line'            => 'nullable',
            ]);

            if ($validator->fails()) {
                $errors = implode(',', $validator->errors()->all());
                $error  = explode(',', $errors);
                return response()->json([
                    "status"     => false,
                    "message"    => 'validation error',
                    "error"      => $error,
                    "error_type" => 1,
                ], 200);

            } else {
                $user             = User::findOrFail(Auth::user()->id);
                $user->name       = $request->input('first_name') . " " . $request->input('last_name');
                $user->first_name = $request->input('first_name');
                $user->last_name  = $request->input('last_name');
                if ($user->email != $request->input('email')) {
                    $user->email_verified = 0;
                }
                if ($user->mobile != $request->input('mobile')) {
                    $user->mobile_verified = 0;
                }

                $user->email  = $request->input('email');
                $user->mobile = $request->input('mobile');
                $user->save();
                if ($user->email_verified == 0) {
                    Mail::to($user->email)->send(new VerifyUser($user));
                }
                $employer = Employer::where('user_id', Auth::user()->id)->update([
                    'address'         => $request->input('address'),
                    'city'            => $request->input('city'),
                    'state'           => $request->input('state'),
                    'country'         => $request->input('country'),
                    'zip'             => $request->input('zip'),
                    'company_ceo'     => $request->input('company_ceo'),
                    'phone_number'    => $request->input('phone_number'),
                    'company_details' => $request->input('company_details'),
                    'linkedin_link'   => $request->input('linkedin_link'),
                    'website_link'    => $request->input('website_link'),
                    'company_name'    => $request->input('company_name'),
                    'tag_line'        => $request->input('tag_line'),
                ]);
                // $employer->address         = $request->input('address');
                // $employer->city            = $request->input('city');
                // $employer->state           = $request->input('state');
                // $employer->country         = $request->input('country');
                // $employer->zip             = $request->input('zip');
                // $employer->company_ceo     = $request->input('company_ceo');
                // $employer->phone_number    = $request->input('phone_number');
                // $employer->company_details = $request->input('company_details');
                // $employer->linkedin_link   = $request->input('linkedin_link');
                // $employer->website_link    = $request->input('website_link');
                // $employer->company_name    = $request->input('company_name');
                // $employer->tag_line        = $request->input('tag_line');
                // $employer->save();

                return response()->json([
                    "status"  => true,
                    "user"    => $user,
                    "message" => "Successfully updated your profile.",
                ], 200);

            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }

    public function companyBookmark(Request $request)
    {
        if (Auth::check()) {
            $validator = Validator::make($request->all(), [
                'employer_id' => 'required',
            ]);

            if ($validator->fails()) {
                $errors = implode(',', $validator->errors()->all());
                $error  = explode(',', $errors);
                return response()->json([
                    "status"     => false,
                    "message"    => 'validation error',
                    "error"      => $error,
                    "error_type" => 1,
                ], 200);

            } else {

                $exist = CompanyBookmark::where(['candidate_id' => Auth::user()->id, 'employer_id' => $request->input('employer_id')])->first();

                if ($exist) {
                    $booked = CompanyBookmark::where(['candidate_id' => Auth::user()->id, 'employer_id' => $request->input('employer_id')])->delete();

                } else {
                    $booked               = new CompanyBookmark;
                    $booked->candidate_id = Auth::user()->id;
                    $booked->employer_id  = $request->input('employer_id');
                    $booked->save();

                }

                if ($booked) {
                    return response()->json([
                        "status"  => true,
                        'message' => "Success.",
                    ], 200);

                } else {
                    return response()->json([
                        "status"     => false,
                        'message'    => "Something went to wrong.",
                        "error_type" => 2,
                    ], 200);
                }
            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }

    }

    public function companyBookmarkList(Request $request)
    {
        if (Auth::check()) {

            $limit  = 5;
            $offset = ((((int) $request->page_no - 1) * $limit));

            $bookmark_companyes = CompanyBookmark::where('candidate_id', Auth::user()->id)->orderBy('id', 'DESC')
                ->with('companyes')
                ->take($limit)
                ->skip($offset)
                ->get();
            $bookmarks = CompanyBookmark::where('candidate_id', Auth::user()->id)
                ->get();

            $total = count($bookmarks);

            if ($bookmark_companyes) {
                return response()->json([
                    "status"             => true,
                    "message"            => "Success.",
                    "bookmark_companyes" => $bookmark_companyes,
                    "per_page"           => $limit,
                    "total"              => $total,
                ], 200);

            } else {
                return response()->json([
                    "status"     => false,
                    'message'    => "Something went to wrong.",
                    "error_type" => 2,
                ], 200);
            }

        } else {
            return response()->json([
                "status"     => false,
                "message"    => "System error, please try after sometime",
                "error_type" => 2,
            ], 200);
        }
    }
}
