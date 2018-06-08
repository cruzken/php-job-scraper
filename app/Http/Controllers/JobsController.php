<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\JobPosting;

class JobsController extends Controller
{
    //
    protected function index()
    {
        $jobPosts = JobPosting::latest()->paginate(10);
        return $jobPosts;
    }
}
