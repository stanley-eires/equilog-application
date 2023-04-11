<?php

use App\Http\Controllers\Admin;
use App\Http\Controllers\MyAccount;
use App\Http\Controllers\PublicController;
use App\Mail\Email;
use App\Models\CohortGroup;
use App\Models\Course;
use App\Models\CoursesUsers;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


//=================================================================





require __DIR__ . '/auth.php';

Route::prefix('myaccount')->group(
    function () {
        Route::middleware(['auth'])->group(function () {
            Route::get('overview', [MyAccount::class, 'overview'])->name('myaccount.overview');
            Route::get('personal-info', [MyAccount::class, 'personalInfo'])->name('myaccount.personal-info');
            Route::get('security', [MyAccount::class, 'security'])->name('myaccount.security');
            Route::get('invoices', [MyAccount::class, 'invoices'])->name('myaccount.invoices');
            Route::get('courses', [MyAccount::class, 'courses'])->name('myaccount.courses');
            Route::post('create-invoice', [MyAccount::class, 'createInvoice'])->name('myaccount.invoice.create');
            Route::post('upload-evidence', [MyAccount::class, 'evidenceUpload'])->name('myaccount.invoice.upload-evidence');
            Route::post('payment-via-paystack', [MyAccount::class, 'handlePaystackPayment'])->name('myaccount.invoice.payment-via-paystack');
        });
    }
);
Route::prefix('admin')->group(
    function () {
        Route::get('/', [Admin::class, 'index'])->name('admin.index')->middleware('auth');
        Route::get('/users', [Admin::class, 'users'])->name('admin.users')->middleware('auth');
        Route::get('/users/create', [Admin::class, 'userCreate'])->name('users.create')->middleware('auth');
        Route::post('/users/create', [Admin::class, 'userSave'])->name('users.save')->middleware('auth');
        Route::post('/users/actions', [Admin::class, 'usersActions'])->name('users.actions')->middleware('auth');
        Route::delete('/users', [Admin::class, 'usersDelete'])->name('users.delete')->middleware('auth');
        Route::get('/user/{id}/edit', [Admin::class, 'userEdit'])->name('user.edit')->middleware('auth');
        Route::put('/user/{id}/edit', [Admin::class, 'userUpdate'])->name('user.update')->middleware('auth');
        Route::get('/user/{id}/{segment}', [Admin::class, 'user'])->name('user.single')->middleware('auth');
        Route::post('/user/course-progress', [Admin::class, 'userCourseProgressActions'])->name('users.course.progress')->middleware('auth');

        Route::post('/cohort/save', [Admin::class, 'cohortSave'])->name('cohort.save')->middleware('auth');
        Route::post('/cohort/add-users', [Admin::class, 'addUsersToCohort'])->name('cohort.add-users')->middleware('auth');
        Route::post('/cohort/remove-user', [Admin::class, 'removeUserFromCohort'])->name('cohort.remove-users')->middleware('auth');
        Route::delete('/cohort', [Admin::class, 'removeCohort'])->name('cohort.delete')->middleware('auth');

        Route::get('/courses', [Admin::class, 'courses'])->name('admin.courses')->middleware('auth');
        Route::get('/courses/create', [Admin::class, 'courseCreate'])->name('course.create')->middleware('auth');
        Route::post('/courses/create', [Admin::class, 'courseSave'])->name('course.save')->middleware('auth');
        Route::delete('/courses', [Admin::class, 'courseDelete'])->name('course.delete')->middleware('auth');
        Route::post('/courses/actions', [Admin::class, 'courseActions'])->name('courses.actions')->middleware('auth');
        Route::post('/course/{id}/edit', [Admin::class, 'courseUpdate'])->name('course.update')->middleware('auth');
        Route::get('/course/{id}/{segment}', [Admin::class, 'Course'])->name('course.single')->middleware('auth');

        Route::get('/invoices', [Admin::class, 'invoices'])->name('admin.invoices')->middleware('auth');
        Route::delete('/invoices', [Admin::class, 'invoicesDelete'])->name('admin.invoices.delete')->middleware('auth');
        Route::post('/invoices', [Admin::class, 'invoicesApprove'])->name('admin.invoices.approve')->middleware('auth');
        Route::put('/invoices', [Admin::class, 'invoicesDecline'])->name('admin.invoices.decline')->middleware('auth');

        Route::get('/site-settings', [Admin::class, 'siteSettings'])->name('admin.site-settings')->middleware('auth');
        Route::post('/site-settings/{key}', [Admin::class, 'saveSiteSettings'])->name('admin.site-settings.save')->middleware('auth');

        Route::post('/maintenance-functions', [Admin::class, 'maintenanceFunctions'])->name('admin.maintenance-functions')->middleware('auth');
    }
);

Route::prefix('seedings')->group(function () {
    Route::post('users', fn () => User::seedUsers())->name('seedings.users');
    Route::post('courses', fn () => Course::seedCourses())->name('seedings.courses');
    Route::post('courses_users', fn () => CoursesUsers::seedCourseUsers())->name('seedings.courses.users');
    Route::post('cohorts', fn () => CohortGroup::seedGroup())->name('seedings.groups');

    Route::post('testmail', function () {
        $subject = "[Equilog] Reactivation of internet login on CU network";
        $content = "This is to remind all faculty and staff that the internet login will be reactivated on the CU network starting this evening.
Your username and password will be resent to your email.
The internet login URL is https://internetlogin.cu.edu.ng/portal?
For any need of support, kindly mail csis.support@covenantuniversity.edu.ng
Thank you";
        Mail::to('stanley@example.com')->send(new Email($subject, $content));
    })->name('seedings.testmail');
});

Route::get('', [PublicController::class, 'index'])->name('public.home');
Route::get('course/{slug}', [PublicController::class, 'course'])->name('public.course.single');
Route::get('/invoice/{invoice}', [MyAccount::class, 'invoice'])->name('invoice')->middleware(['auth', 'verified']);
Route::delete('/invoice', [MyAccount::class, 'invoiceRemoveItem'])->name('invoice.remove-item')->middleware(['auth', 'verified']);
