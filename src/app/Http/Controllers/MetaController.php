<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class MetaController extends Controller
{
    public function facebookLogin(Request $request) {

        Log::info('Request Data: '. $request);
    }
}
