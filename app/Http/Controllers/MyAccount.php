<?php

namespace App\Http\Controllers;

use App\Mail\Email;
use App\Models\Activity;
use App\Models\Course;
use App\Models\CoursesUsers;
use App\Models\Invoice;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use stdClass;

class MyAccount extends Controller
{
    public function overview(Request $request)
    {
        $data['title'] = "My Account - Overview";
        return Inertia::render('MyAccount/Overview', $data);
    }
    public function personalInfo(Request $request)
    {
        $data['title'] = "Personal Info";
        $data['user'] = collect(Auth::user())->forget(['created_at', 'updated_at']);
        return Inertia::render('MyAccount/PersonalInfo', $data);
    }
    public function security(Request $request)
    {
        $data['title'] = "Security";
        $data['activities'] = Activity::where(['user_id' => $request->user()->id, 'actions' => 'Login'])->latest()->get();
        return Inertia::render('MyAccount/Security', $data);
    }
    public function courses(Request $request)
    {
        $data['title'] = "Enrolled Courses";
        $data['courses'] =
            CoursesUsers::join('users', 'users.id', '=', 'courses_users.user_id')->join('courses', 'courses.id', '=', 'courses_users.course_id')->join('invoices', 'courses_users.invoice_id', '=', 'invoices.id')->select('users.id as user_id', 'users.name', 'users.profile_picture')->addSelect('courses_users.created_at as created_at', 'invoice_id', 'progress', 'date_completed', 'invoice_ref', 'courses_users.id')->addSelect('courses.name as course_name', 'courses.program as course_program', 'courses.id as course_id', 'courses.slug as course_slug')->where('courses_users.user_id', Auth::id())->orderBy('name')->latest()->paginate();
        return Inertia::render('MyAccount/Courses', $data);
    }
    public function invoices(Request $request)
    {
        $invoices = Invoice::join('users', 'users.id', '=', 'invoices.user_id')->select('invoices.id as invoice_id', 'invoice_ref', 'amount', 'invoices.status', 'date_approved', 'payment_status', 'date_paid', 'invoices.created_at', 'items')->addSelect('users.name', 'users.id as user_id', 'profile_picture')->where('users.id', Auth::id())->latest()->paginate();

        $data['invoices'] = $invoices;

        $data['title'] = "Payments & Invoices";

        return Inertia::render('MyAccount/Invoices', $data);
    }
    public function invoice(Request $request, $invoice)
    {

        $data['invoice'] = Invoice::join('users', 'users.id', '=', 'invoices.user_id')->select('invoice_ref', 'items', 'invoices.status', 'amount', 'invoices.created_at', 'users.name', 'payment_status', 'invoices.id', 'users.id as user_id')->findOrFail($invoice);

        $data['invoice']->transactions = Transaction::select('created_at', 'transaction_gateway', 'id', 'status', 'amount', 'meta')->where('invoice_id', $invoice)->get();

        $data['title'] = "#{$data['invoice']->invoice_ref} | (NGN " . number_format($data['invoice']->amount) . ")";

        return Inertia::render('MyAccount/Invoice', $data);
    }

    public function invoiceRemoveItem(Request $request)
    {
        $input = $request->post();
        $invoice = Invoice::select('items')->find($input['id'])->items;
        $items = array_values(array_filter($invoice, fn ($item) => $item['id'] != $input['item']));
        $amount = array_sum(array_column($items, 'cost'));
        Invoice::where('id', $input['id'])->update(['items' => json_encode($items), 'amount' => $amount]);
        return back();
    }

    public function createInvoice(Request $request)
    {
        $course = Course::select('id', 'name', 'cost', 'discounted_cost')->findOrFail($request->post('course_id'));
        $invoice = Invoice::select('id', 'items')->where(['user_id' => Auth::id(), 'status' => 0, 'payment_status' => null])->first();
        $item = [
            "id" => $course->id, "description" => $course->name, 'cost' => $course->discounted_cost ?? $course->cost
        ];
        if ($invoice) {
            $invoice_items = $invoice->items;
            array_push($invoice_items, $item);
            $invoice_items = array_unique($invoice_items, SORT_REGULAR);
            Invoice::where(['id' => $invoice->id, 'user_id' =>  Auth::id()])->update(['items' => $invoice_items, 'amount' => array_sum(array_column($invoice_items, 'cost'))]);
            return to_route('invoice', $invoice->id);
        } else {
            $id = Invoice::create([
                'user_id' => Auth::id(),
                'invoice_ref' => date('Ymd') . mt_rand(100, 999),
                'items' => array($item),
                'amount' => $course->discounted_cost ?? $course->cost
            ]);
            return to_route('invoice', $id);
        }
    }

    public function evidenceUpload(Request $request)
    {

        $entry = $request->all();
        if ($request->hasFile('file') && $request->file('file')->isValid()) {
            $entry['meta']['proof_of_payment'] = $request->file('file')->storeAs(date('Y'), $request->file('file')->getClientOriginalName(), 'public');
        }
        Transaction::create($entry);
        Invoice::where('id', $entry['invoice_id'])->update(['payment_status' => 1, 'date_paid' => date("M d, Y h:i A")]);
        Activity::create([
            'actions' => 'invoice_actions',
            'user_id' => Auth::id(),
            'value' => [
                'id' => $entry['invoice_id'],
                'type' => ' made payment and uploaded evidence of payment about'
            ],
        ]);
        $user = $request->user();
        $invoice_link = route('invoice', [$entry['invoice_id']]);
        $content = "<h1>Dear {$user['name']}</h1>
        <p>The administrators have been notified of your payment. You should get a mail shortly regarding the outcome of the evidence verification</p>
        <p>You can view the invoice by clicking on the link <a href='$invoice_link'>$invoice_link</a></p>
        <p>Thank you for choosing equilog</p>";

        Mail::to($user)->send(new Email("Evidence Upload Acknowlegement", $content));
        $content = "<h1>Dear Administrator</h1>
        <p>A candidate <strong>{$user['name']}</strong> have just uploaded an evidence of payment via Bank transfer for some transactions worth <strong>NGN" . number_format($entry['amount']) . "</strong>. Please you are required to <a href='" . route('admin.invoices') . "'>verify and approve or decline</a> the payment made</p>
        <p>You can view the invoice by clicking on the link <a href='$invoice_link'>$invoice_link</a></p>
        <p>Thank you</p>";
        Mail::to(env('MAIL_FROM_ADDRESS'))->send(new Email("[Action Required] New Payment Made", $content));

        return back()->with('message', [
            'title' => "Payment Evidence",
            'content' => "Your payment evidence file has been uploaded",
            'status' => 'success'
        ]);
    }

    public function handlePaystackPayment(Request $request)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://api.paystack.co/transaction/verify/{$request->reference}",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                "Authorization: Bearer sk_test_1c22c679496097ff2a4eaffc8cd24c95ff27bab1",
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);
        if ($err) {
            return back()->with('message', [
                'title' => "Transaction Unsuccessful", 'content' => $err, 'status' => 'danger'
            ]);
        } else {
            $response = json_decode($response);
            if (!$response->status || $response->data->status !== 'success') {
                Transaction::create(['amount' => ($response->data->amount / 100), 'transaction_gateway' => "Paystack", 'status' => 0, 'meta' => $response->data, 'invoice_id' => $request->invoice]);
                return back()->with('message', [
                    'title' => "Transaction Unsuccessful", 'content' => $response->message, 'status' => 'danger'
                ]);
            } else {
                $invoice = Invoice::select('id', 'amount', 'payment_status', 'date_paid', 'items')->where('id', $request->invoice)->first();
                if ($invoice->payment_status == 1) {
                    Transaction::create(['amount' => ($response->data->amount / 100), 'transaction_gateway' => "Paystack", 'status' => 0, 'meta' => $response->data, 'invoice_id' => $invoice->id]);
                    return back()->with('message', [
                        'title' => "Transaction unsuccessful", 'content' => "You have already paid for this invoice on {$invoice->date_paid}", 'status' => 'danger'
                    ]);
                } elseif ($invoice->amount != ($response->data->amount / 100)) {
                    Transaction::create(['amount' => ($response->data->amount / 100), 'transaction_gateway' => "Paystack", 'status' => 0, 'meta' => $response->data, 'invoice_id' => $invoice->id]);
                    return back()->with('message', [
                        'title' => "Transaction unsuccessful", 'content' => "You did not pay the correct amount. You were supposed to pay NGN " . number_format($invoice->amount) .
                            " but paid NGN " . number_format(($response->data->amount / 100)), 'status' => 'danger'
                    ]);
                } else {
                    Transaction::create(['amount' => ($response->data->amount / 100), 'transaction_gateway' => "Paystack", 'status' => 1, 'meta' => $response->data, 'invoice_id' => $invoice->id]);
                    Invoice::where('id', $invoice->id)->update(['payment_status' => 1, 'date_paid' => date("M d, Y h:i A"), 'status' => 2, 'date_approved' => date("M d, Y h:i A")]);
                    Activity::create([
                        'actions' => 'invoice_actions',
                        'user_id' => Auth::id(),
                        'value' => [
                            'id' => $invoice->id,
                            'type' => ' made full payment on'
                        ],
                    ]);
                    foreach ($invoice->items as $item) {
                        CoursesUsers::create([
                            'course_id' => $item['id'],
                            'user_id' => Auth::id(),
                            'invoice_id' => $invoice->id,
                            'date_completed' =>  null
                        ]);
                    }
                    return back()->with('message', [
                        'title' => "Transaction Successful", 'content' => "Thank you for purchasing a course(s) with us", 'status' => 'success'
                    ]);
                }
            }
            return back();
        }
    }
}
