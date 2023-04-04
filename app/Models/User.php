<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasUuids, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name', 'email', 'password', 'phone', 'date_of_birth', 'gender', 'marital_status', 'status', 'address', 'login_at', 'summary', 'work_experience', 'education', 'medicals', 'nin', 'next_of_kin', 'roles', 'profile_picture'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'medicals' => 'array',
        'education' => 'array',
        'work_experience' => 'array',
        'next_of_kin' => 'array',
        'roles' => 'array',
    ];

    public function courses()
    {
        return $this->hasMany(CoursesUsers::class, 'user_id');
    }

    public static function  seedUsers()
    {
        DB::table('users')->truncate();
        $faker = \Faker\Factory::create();
        $fixed_date = strtotime('10 years ago');
        for ($i = 0; $i < 100; $i++) {
            $user['name'] = $faker->name;
            $user['email'] = $faker->email;
            $user['password'] = Hash::make('password');
            $user['gender'] = $faker->randomElement(['Male', 'Female']);
            $user['marital_status'] = $faker->randomElement(['Single', 'Married', 'Divorced', 'Widowed']);
            $user['phone'] = $faker->phoneNumber;
            $user['nin'] = $faker->randomNumber(9);
            $user['date_of_birth'] = date("Y-m-d", mt_rand($fixed_date, time()));
            $user['address'] = $faker->address;
            $user['status'] = mt_rand(0, 10) != 0;
            if (mt_rand(0, 2) == 1) {
                $user['login_at'] = date("M d, Y h:i A", mt_rand($fixed_date, time()));
            }
            $user['summary'] = $faker->realText();
            $user['next_of_kin'] = [
                'name' => $faker->name,
                'email' => $faker->email,
                'phone' => $faker->phoneNumber,
                'relationship' => $faker->randomElement(['Sibling', 'Parent', 'Child', 'Friend', 'Others']),
            ];
            $rand = mt_rand(0, 1);
            $work_start = mt_rand($fixed_date, time());
            $work_end = ($rand == 0) ? date("Y-m", mt_rand($work_start, time())) : 'Current';

            $user['work_experience'] = [
                'title' => $faker->jobTitle,
                'employer' => $faker->company,
                'location' => $faker->address,
                'date' => date("Y-m", ($work_start)) . ' - ' . $work_end
            ];
            $rand = mt_rand(0, 1);
            $work_start = mt_rand($fixed_date, time());
            $work_end = ($rand == 0) ? date("Y-m", mt_rand($work_start, time())) : 'Current';
            $user['education'] = [
                'degree' => $faker->randomElement(['School Certificate', 'National Diploma', 'Bachelor Degree', 'Master Degree', 'Doctoral Degree']),
                'institution' => $faker->company,
                'location' => $faker->address,
                'date' => date("Y-m", ($work_start)) . ' - ' . $work_end
            ];
            $user['medicals'] = [
                'genotype' => $faker->randomElement(['AA', 'AO', 'BB', 'BO', 'AB', 'OO']),
                'bloodgroup' => $faker->randomElement(['A', 'B', 'AB', 'O']),
                'rhd' => $faker->randomElement(['Positive', 'Negative']),
                'height' => $faker->randomFloat(1, 4, 8) . ' FT',
                'weight' => $faker->randomFloat(1, 4, 8) . ' KG',
                'hearing' => $faker->randomElement(['Clear', 'Partial', 'Deaf']),
                'vision' => $faker->randomElement(['Clear', 'Blurry', 'Blind']),
                'hiv' => $faker->randomElement(['Positive', 'Negative']),
                'covid' => $faker->randomElement(['Positive', 'Negative']),
                'meningitis' => $faker->randomElement(['Positive', 'Negative']),
                'tuberculosis' => $faker->randomElement(['Positive', 'Negative'])
            ];

            self::create($user);
        }
        back();
    }
}
