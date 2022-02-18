<?php

namespace App\Http\Controllers;

use App\Models\Task;

class TaskController extends Controller
{
    public function index()
    {
        return Task::orderBy('startDate', 'desc')->limit(50)->get();
    }
}
