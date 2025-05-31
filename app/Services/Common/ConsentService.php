<?php

namespace App\Services\Common;

use App\Models\ConsentUser;

class ConsentService
{
    public function handleJobConsent($data)
    {
        ConsentUser::create(['email' => $data['email']]);
    }
}
