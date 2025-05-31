<?php

namespace App\Services\Admin;

use App\Models\JobPost;
use Illuminate\Support\Facades\Auth;

class JobPostService
{
    public function handleShowJobInHome($id)
    {
        $jobPost = JobPost::find($id);
        $update = $jobPost->update([
            'show_in_home' => $jobPost->show_in_home == 0 ? 1 : 0,
            'updated_by' => Auth::id()
        ]);

        return $update;
    }
}
