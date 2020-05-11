<?php

namespace App\Http\Controllers;

use App\Traits\MakesRemoteHttpRequests;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class GatewayController extends Controller
{
    use MakesRemoteHttpRequests;
}
