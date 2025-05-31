<?php

namespace App\Http\Controllers\Admin;

use App\Services\Payment\SubscriptionService;
use Illuminate\Http\Request;

class SubscriptionController
{

    private $subscriptionService;

    public function __construct(SubscriptionService $subscriptionService)
    {
        $this->subscriptionService = $subscriptionService;
    }

    public function index(Request $request)
    {
        $keyword = request('keyword') ? request('keyword') : '';
        $data = $this->subscriptionService->find_all_waiting_subscription($keyword);
        return view('admin.subscription.list', compact('data', 'request'));
    }

    public function activateSubscription($id)
    {
        $data = $this->subscriptionService->activate_waiting_subscription($id);
        if ($data['result']) {
            return response()->json(['success' => $data]);
        } else {
            return response()->json(['error' => $data]);
        }
    }

}
