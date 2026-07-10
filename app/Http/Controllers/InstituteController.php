<?php

namespace App\Http\Controllers;

use App\InstituteContent;
use Illuminate\View\View;

class InstituteController extends Controller
{
    public function lectures(InstituteContent $content): View
    {
        return view('institute.lectures', $content->lectures());
    }

    public function live(InstituteContent $content): View
    {
        return view('institute.live', $content->live());
    }

    public function institute(InstituteContent $content): View
    {
        return view('institute.index', $content->institute());
    }

    public function announcements(InstituteContent $content): View
    {
        return view('institute.announcements', ['announcements' => $content->announcements()]);
    }
}
