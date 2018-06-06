<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JobPosting extends Model
{
    //
    protected $fillable = ['title', 'company', 'location', 'link'];
}
