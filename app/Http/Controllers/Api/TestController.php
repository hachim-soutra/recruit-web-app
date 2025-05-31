<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function testMail(Request $request)
    {
        $headers = 'From: <admin@nfcmate.com>' . "\r\n" .
            'Reply-To: <admin@nfcmate.com>';

        mail('<shibsankarjana2@gmail.com>', 'the subject', 'the message', $headers,
            '-fwebmaster@example.com');

    }

}
