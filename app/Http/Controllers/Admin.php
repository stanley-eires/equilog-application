<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Models\Course;
use App\Models\CoursesUsers;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Illuminate\Support\Str;
use stdClass;

class Admin extends Controller
{
    public function users(Request $request)
    {
        $data['users'] = User::select('name', 'id', 'email', 'status', 'profile_picture', 'login_at')->orderBy('name', 'asc')->withCount('courses')->paginate(10);
        $data['title'] = "Platform Users";
        return Inertia::render('Admin/Users', $data);
    }
    public function user(Request $request, $id, $segment)
    {
        $user = User::select('id', 'profile_picture', 'name', 'email', 'gender', 'marital_status', 'phone', 'date_of_birth', 'address');
        if ($segment == 'profile') {
            $user = $user->addSelect('summary', 'work_experience', 'education', 'medicals');
            $data['user'] = $user->where('id', $id)->findOrFail($id);
            $data['title'] = "{$data['user']->name} - Profile";
        } elseif ($segment == 'courses') {
            $data['user'] = $user->where('id', $id)->findOrFail($id);
            $data['title'] = "{$data['user']->name} - Enrolled Courses";
            $data['user']->courses =
                CoursesUsers::join('users', 'users.id', '=', 'courses_users.user_id')->join('courses', 'courses.id', '=', 'courses_users.course_id')->join('invoices', 'courses_users.invoice_id', '=', 'invoices.id')->select('users.id as user_id', 'users.name', 'users.profile_picture')->addSelect('courses_users.created_at as created_at', 'invoice_id', 'progress', 'date_completed', 'invoice_ref', 'courses_users.id')->addSelect('courses.name as course_name', 'courses.program as course_program', 'courses.id as course_id')->where('courses_users.user_id', $id)->orderBy('name')->latest()->paginate();
        } elseif ($segment == 'transactions') {
            $user = $user->where('id', $id)->findOrFail($id);
            $data['title'] = "{$user->name} - Transactions";

            $user->invoices = Invoice::join('users', 'users.id', '=', 'invoices.user_id')->select('invoices.id as invoice_id', 'invoice_ref', 'amount', 'invoices.status', 'date_approved', 'payment_status', 'date_paid', 'invoices.created_at', 'items')->addSelect('users.name', 'users.id as user_id', 'profile_picture')->where(['invoices.user_id' => $id])->latest()->paginate();


            $data['user'] = $user;
        }
        return Inertia::render("Admin/User/" . ucfirst($segment), $data);
    }
    public function userCreate()
    {
        $data['title'] = 'Create User';
        $data['user'] = [
            'name' => null, 'email' => null, 'phone' => null, 'date_of_birth' => null, 'gender' => null, 'marital_status' => null, 'address' => null, 'summary' => null
        ];
        return Inertia::render("Admin/User/Modify", $data);
    }
    public function userSave(Request $request)
    {
        $request->validate([
            'email' => 'required|unique:users', 'name' => 'required|unique:users'
        ]);
        $input = $request->post();
        $input['password'] = Hash::make('password');
        $input['status'] = 1;
        $id = User::create($input);
        if ($id) {
            return to_route('user.edit', [$id]);
        }
        return back();
    }
    public function userEdit(Request $request, $id)
    {
        $data['title'] = 'Edit Profile';
        $data['user'] = User::select('id', 'name', 'email', 'gender', 'marital_status', 'phone', 'date_of_birth', 'profile_picture', 'address', 'summary', 'roles')->findOrFail($id);
        return Inertia::render("Admin/User/Modify", $data);
    }
    public function userUpdate(Request $request, $id)
    {
        $input = $request->except('_method');
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $input['profile_picture'] = $request->file('profile_picture')->storeAs(date('Y'), $request->file('profile_picture')->getClientOriginalName(), 'public');
        }

        User::where('id', $id)->update($input);
        return back()->with('message', [
            'title' => 'User Profile',
            'content' => "Changes Updated",
            'status' => 'success'
        ]);
    }

    public function usersActions(Request $request)
    {
        User::whereIn('id', $request->only('id')['id'])->update($request->except('id'));
        return back();
    }
    public function usersDelete(Request $request)
    {
        User::whereIn('id', $request->only('id')['id'])->delete();
        return back();
    }

    public function userCourseProgressActions(Request $request)
    {
        CoursesUsers::whereIn('id', $request->only('id')['id'])->update($request->except('id'));
        return back();
    }
    public function courses(Request $request)
    {
        $data['courses'] = Course::orderBy('name', 'asc')->withCount('courses')->get();
        $data['title'] = "All Courses";

        return Inertia::render('Admin/Courses/Courses', $data);
    }
    public function course(Request $request, $id, $segment)
    {
        if ($segment == 'overview') {
            $course = Course::findOrFail($id);

            $stat = CoursesUsers::join('courses', 'courses.id', '=', 'courses_users.course_id')->where('course_id', $id)->selectRaw('count(name) as total_enrollment, sum(cost) as revenue')->first();
            $course->revenue = $stat->revenue;
            $course->total_enrollment = $stat->total_enrollment;
            $course->total_completed = CoursesUsers::where(['course_id' => $id, 'progress' => 1])->count();

            $data['title'] = "{$course->name} - Overview";
        } elseif ($segment == 'enrollments') {
            $course = Course::select('name', 'id')->findOrFail($id);
            $data['title'] = "{$course->name} - Enrollments";
            $course->enrollments = CoursesUsers::join('users', 'users.id', '=', 'courses_users.user_id')->join('courses', 'courses.id', '=', 'courses_users.course_id')->join('invoices', 'courses_users.invoice_id', '=', 'invoices.id')->select('users.id as user_id', 'users.name', 'users.profile_picture')->addSelect('courses_users.created_at as created_at', 'invoice_id', 'progress', 'date_completed', 'invoice_ref', 'courses_users.id')->addSelect('courses.name as course_name', 'courses.program as course_program', 'courses.id as course_id')->where('courses_users.course_id', $id)->orderBy('name')->latest()->paginate();
        } elseif ($segment == 'modify') {
            $course = Course::select('id', 'name', 'summary', 'description', 'status', 'program', 'cost', 'discounted_cost', 'duration', 'learning_methods', 'date_of_commencement', 'banner')->findOrFail($id);
            $data['title'] = "Edit {$course->name}";
        }
        $data['course'] = $course;
        return Inertia::render("Admin/Courses/" . ucfirst($segment), $data);
    }
    public function courseCreate()
    {
        $data['title'] = 'Create Course';
        $data['course'] = ['name' => null, 'summary' => null, 'description' => null, 'status' => null, 'program' => null, 'cost' => null, 'discounted_cost' => null, 'duration' => null, 'banner' => null, 'date_of_commencement' => null, 'learning_methods' => ['virtual' => true, 'inclass' => true], 'banner' => null];
        return Inertia::render("Admin/Courses/Modify", $data);
    }
    public function courseSave(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:courses'
        ]);
        $input = $request->post();
        $input['status'] = 1;
        $input['slug'] = Str::slug($input['name'], '-');
        $input['user_id'] = User::select('id')->first()->id;
        if ($request->hasFile('banner') && $request->file('banner')->isValid()) {
            $input['banner'] = $request->file('banner')->storeAs(date('Y'), $request->file('banner')->getClientOriginalName(), 'public');
        }
        $id = Course::create($input);
        if ($id) {
            return to_route('course.single', [$id, 'modify']);
        }
        return back()->with('message', [
            'title' => "New Course Created",
            'status' => 'success'
        ]);
    }
    public function courseUpdate(Request $request, $id)
    {
        $input = $request->post();
        if ($request->hasFile('banner') && $request->file('banner')->isValid()) {
            $input['banner'] = $request->file('banner')->storeAs(date('Y'), $request->file('banner')->getClientOriginalName(), 'public');
        }
        Course::where('id', $id)->update($input);
        return back()->with('message', [
            'title' => "Course Update",
            'content' => 'Changes has bee saved',
            'status' => 'success'
        ]);
    }
    public function courseActions(Request $request)
    {
        Course::whereIn('id', $request->only('id')['id'])->update($request->except('id'));
        return back()->with('message', [
            'title' => "Course Actions",
            'content' => "Changes Updated Successfully",
            'status' => 'success'
        ]);
    }
    public function courseDelete(Request $request)
    {
        Course::whereIn('id', $request->only('id')['id'])->delete();
        return back();
    }


    public function invoices(Request $request)
    {
        $invoices = Invoice::join('users', 'users.id', '=', 'invoices.user_id')->select('invoices.id as invoice_id', 'invoice_ref', 'amount', 'invoices.status', 'date_approved', 'payment_status', 'date_paid', 'invoices.created_at', 'items')->addSelect('users.name', 'users.id as user_id', 'profile_picture')->latest()->paginate();

        $data['invoices'] = $invoices;

        $data['title'] = "Payments & Invoices";

        return Inertia::render('MyAccount/Invoices', $data);
    }
    public function invoicesDelete(Request $request)
    {
        Invoice::whereIn('id', $request->only('id')['id'])->delete();
        return back();
    }
    public function invoicesApprove(Request $request)
    {
        $input = $request->post();

        Invoice::where('id', $input['id'])->update([
            'status' => 2,
            'date_approved' => date("M d, Y h:i A")
        ]);
        foreach ($input['items'] as $item) {
            CoursesUsers::create([
                'course_id' => $item,
                'user_id' => $input['user_id'],
                'invoice_id' => $input['id'],
                'date_completed' =>  null
            ]);
        }
        $user = User::select('email', 'name')->find($input['user_id']);
        $invoice_link = route('invoice', [$input['id']]);
        $content = "<h1>Dear {$user->name}</h1>
        <p>Your payment has been approved. You can now find your available courses at the courses section of your account</p>
        <p>You can view this invoice by clicking on the link <a href='$invoice_link'>$invoice_link</a></p>
        <p>Thank you for choosing equilog</p>";
        Mail::to($user)->send(new Email("Payment Approval", $content));
        return back()->with('message', [
            'title' => 'Invoice Status',
            'content' => "Payment Approved Successfully",
            'status' => 'success'
        ]);
    }

    public function invoicesDecline(Request $request)
    {
        Invoice::whereIn('id', $request->only('id')['id'])->update($request->except('id'));
        $input = $request->all();
        $user = User::select('email', 'name')->find($input['user_id']);
        $content = "<h1>Dear {$user->name}</h1>
        <p>Your payment and the evidence was declined. Kindly contact the administrator for more information </p>";
        Mail::to($user)->send(new Email("Payment Declined", $content));
        return back()->with('message', [
            'title' => 'Invoice Status',
            'content' => "You declined invoice payment",
            'status' => 'warning'
        ]);
    }
}