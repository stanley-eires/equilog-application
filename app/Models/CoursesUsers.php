<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CoursesUsers extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id', 'user_id', 'invoice_id', 'date_completed', 'progress'
    ];


    public static function seedCourseUsers()
    {
        self::truncate();
        Transaction::truncate();
        Invoice::truncate();

        $users = User::select('id')->inRandomOrder()->get();
        $courses = Course::select('id', 'name', 'cost')->where('status', 1)->inRandomOrder()->get();

        foreach ($users as $key) {
            $items = [];
            for ($i = 0; $i < mt_rand(1, count($courses)); $i++) {
                $items[] = [
                    'id' => $courses[$i]->id, 'description' => $courses[$i]->name,
                    'cost' => $courses[$i]->cost
                ];
            }
            if (!empty($items)) {
                Invoice::create([
                    'user_id' => $key->id,
                    'invoice_ref' => date('Ymd') . mt_rand(100, 999),
                    'items' => $items,
                    'amount' => array_sum(array_column($items, 'cost')),
                ]);
            }
        }
        $faker = \Faker\Factory::create();
        $invoices = Invoice::select('id', 'amount')->inRandomOrder()->get();
        foreach ($invoices as $key) {
            $payment_status = $faker->randomElement([NULL, 0, 1, 1, 1, 1,1,1]);
            if ($payment_status !== NULL) {
                $gateway = $faker->randomElement(['Bank Transfer', 'Bank Transfer', 'Paystack']);
                Transaction::create([
                    'amount' => $key->amount,
                    'transaction_gateway' => $gateway,
                    'status' => $payment_status,
                    'invoice_id' => $key->id,
                    'meta' => [
                        'proof_of_payment' => '2023/pop.jpg'
                    ]
                ]);
                Invoice::where('id', $key->id)->update(['payment_status' => $payment_status, 'date_paid' => date("M d, Y h:i A", mt_rand(1610000000, time()))]);
            }
        }
        $invoices = Invoice::select('user_id', 'items', 'id')->where('payment_status', 1)->get();

        foreach ($invoices as $key) {
            $approved = $faker->randomElement([0, 1, 1, 2, 2, 2, 2, 2, 2,2,2]);
            if ($approved > 0) {
                Invoice::where('id', $key->id)->update([
                    'status' => $approved,
                    'date_approved' => date("M d, Y h:i A", mt_rand(1610000000, time()))
                ]);
                if ($approved == 2) {
                    foreach ($key->items as $course_item) {
                        $status = mt_rand(0, 1);
                        CoursesUsers::create([
                            'course_id' => $course_item['id'],
                            'user_id' => $key->user_id,
                            'invoice_id' => $key->id,
                            'progress' => $status,
                            'date_completed' => $status ? date("M d, Y h:i A", mt_rand(1610000000, time())) : null
                        ]);
                    }
                }
            }
        }
        return back();
    }
}
