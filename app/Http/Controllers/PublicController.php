<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CoursesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function index()
    {
        $data['title'] = "Welcome to Equilog";
        $data['courses'] = Course::select('slug', 'name', 'program', 'summary', 'cost', 'discounted_cost', 'duration', 'date_of_commencement', 'banner')->where('status', 1)->get();
        return Inertia::render('Public/Landing', $data);
    }

    public function course(Request $request, $slug)
    {
        $data['course'] = Course::where('slug', $slug)->firstOrFail();
        $data['my_application'] = CoursesUsers::select('created_at')->where(['course_id' => $data['course']->id, 'user_id' => Auth::id()])->first();
        $data['title'] = $data['course']->name;
        return Inertia::render('Public/CourseSingle', $data);
    }
}
