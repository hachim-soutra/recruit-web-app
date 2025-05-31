<?php

namespace App\Http\Controllers\Admin;

use App\Enum\EventStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\EventRegister;
use App\Models\User;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Enum;
use Validator;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $keyword = $request->input("keyword", null);
        $data    = Event::orderBy('id', 'DESC')
            ->where(function ($query) use ($keyword) {
                if ($keyword) {
                    $query->orWhere("title", "like", "%" . $keyword . "%");
                    $query->orWhere("slug", "like", "%" . $keyword . "%");
                    $query->orWhere("event_date", "like", "%" . $keyword . "%");
                    $query->orWhere("time", "like", "%" . $keyword . "%");
                    $query->orWhere("status", "like", "%" . $keyword . "%");
                }
            })
            ->paginate(10);
        return view('admin.event.list', compact('data', 'request'));
    }

    public function create()
    {
        return view('admin.event.add');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title'                 => 'required|max:255',
            'image'                 => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'event_date'            => 'required|date|date_format:Y-m-d|after:today',
            'time'                  => 'required',
            'details'               => 'required',
            'registration_link'     => 'required|url',
            'status'                => ['required', new Enum(EventStatusEnum::class)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        $event                      = new Event;
        $event->title               = $request->input('title');
        $event->slug                = Str::slug($request->input('title')) . Str::random(5);
        $event->event_date          = $request->input('event_date');
        $event->time                = $request->input('time');
        $event->details             = $request->input('details', null);
        $event->registration_link   = $request->input('registration_link');
        $event->status              = $request->input('status');

        if ($request->hasFile('image')) {
            $time      = Carbon::now();
            $file      = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $directory = date_format($time, 'Y') . '/' . date_format($time, 'm') . '/';
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move(public_path('uploads/event/' . $directory), $filename);
            $event->image = $directory . $filename;
        }

        $event->created_by = Auth::user()->id;

        if ($event->save()) {

            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Events has been created successfully.',
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while creating the job post.',
                ],
            ], 200);
        }
    }

    public function show($id)
    {
        $event      = Event::where('id', $id)->first();
        $event_user = EventRegister::where('events_id', $id)->with('r_users')->get();
        return view('admin.event.show', compact('event', 'event_user'));
    }

    public function edit($id)
    {
        $event = Event::where('id', $id)->first();
        return view('admin.event.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title'                 => 'required|max:255',
            'image'                 => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'event_date'            => 'required|date|date_format:Y-m-d|after:today',
            'time'                  => 'required',
            'details'               => 'required',
            'registration_link'     => 'required|url',
            'status'                => ['required', new Enum(EventStatusEnum::class)],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'data' => [
                    "status"  => 'validation_error',
                    "message" => $validator->errors(),
                ],
            ], 200);
        }

        $event                      = Event::where('id', $id)->first();
        $event->title               = $request->input('title');
        $event->slug                = Str::slug($request->input('title')) . Str::random(5);
        $event->event_date          = $request->input('event_date');
        $event->time                = $request->input('time');
        $event->details             = $request->input('details', null);
        $event->registration_link   = $request->input('registration_link');
        $event->status              = $request->input('status');

        if ($request->hasFile('image')) {
            if ($event->image) {
                if (File::exists("uploads/event/" . $event->path)) {
                    File::delete("uploads/event/" . $event->path);
                }
            }
            $time      = Carbon::now();
            $file      = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $directory = date_format($time, 'Y') . '/' . date_format($time, 'm') . '/';
            $filename  = Str::random(5) . date_format($time, 'd') . rand(1, 9) . date_format($time, 'h') . "." . $extension;
            $file->move(public_path('uploads/event/' . $directory), $filename);
            $event->image = $directory . $filename;
        }

        if ($event->save()) {
            return response()->json([
                'data' => [
                    "status"  => 'success',
                    'message' => 'Events has been updated successfully.',
                ],
            ], 200);
        } else {
            return response()->json([
                'data' => [
                    "status"  => 'error',
                    'message' => 'Sorry a problem occurred while creating the job post.',
                ],
            ], 200);
        }
    }

    public function destroy($id)
    {
        $event = Event::where('id', $id)->first();
        if ($event) {
            $event->is_delete  = 0;
            $event->updated_by = Auth::user()->id;
            $event->save();
            return response()->json([
                'success' => [
                    'message' => "Events has been removed",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Events Not Found",
                ],
            ], 200);
        }
    }

    public function status(Request $request)
    {
        $event = Event::where('id', $request->input('id'))->first();
        if ($event) {
            $event->status     = $request->input('status');
            $event->updated_by = Auth::user()->id;
            $event->save();
            return response()->json([
                'success' => [
                    'message' => "Events has been Updated",
                ],
            ], 200);
        } else {
            return response()->json([
                'error' => [
                    'message' => "Events Not Found",
                ],
            ], 200);
        }
    }
}
