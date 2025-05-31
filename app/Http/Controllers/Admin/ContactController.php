<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Support\Facades\Mail;
use App\Mail\ContactReplyMail;
use Validator;
use Auth;

class ContactController extends Controller
{
    public function info(){
        $data = Contact::where('is_deleted', '0')->orderBy('id', 'DESC')->paginate(10);
        return view('admin.contact.info', compact('data'));
    }

    public function trash($id = null){
        if($id != ''){
            $id = base64_decode($id);
            Contact::where('id', $id)->update([
                'is_deleted' => '1'
            ]);
            return redirect()->back()->withSuccess("Contact Information Deleted.");
        }
        return redirect()->back()->withError("Something Went Wrong.");
    }

    public function fullview($id = null){
        if($id != ''){
            $id = base64_decode($id);
            $data = Contact::where('id', $id)->first();
            return view('admin.contact.fullview', compact('data'));
        }
        return redirect()->back()->withError("Something Went Wrong.");
    }

    public function reply(Request $request){
        $validator = Validator::make($request->all(), [
            'response_message'          => 'required|string',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator->errors()->messages());
        }else{
            $id = base64_decode($request->input('rowid'));
            Contact::where('id', $id)->update([
                'status' => 'Replied',
                'replymsg' => $request->input('response_message'),
                'replied_by' => Auth::id()
            ]);
            $data = Contact::where('id', $id)->first();
            try {
                Mail::to($data->email)->send(new ContactReplyMail($data));
                return redirect()->back()->withSuccess('Mail Response Send Successfully.');
            } catch (\Throwable $th) {
                return redirect()->back()->withError('Something Went Wrong.');
            }
        }
    }
}
