<?php

namespace App\Controllers;

class ActivityLogController extends BaseController
{
    public function index()
    {
        return view('activity-log/list', []);
    }
}
