<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class Course extends Model
{
    use HasFactory, HasUuids, SoftDeletes;

    protected $fillable = [
        'name', 'summary', 'description', 'status', 'program', 'cost', 'discounted_cost', 'duration', 'learning_methods', 'date_of_commencement', 'banner', 'user_id', 'slug'
    ];
    protected $attributes = [
        'learning_methods' => '["virtual","inclass"]',
    ];
    protected $casts = [
        'learning_methods' => 'array',
    ];
    public function courses()
    {
        return $this->hasMany(CoursesUsers::class, 'course_id');
    }


    public static function  seedCourses()
    {
        self::truncate();
        $user = User::select('id')->first();
        $faker = \Faker\Factory::create();
        foreach ([['Digital Entrepreneurship', 'Digital Entrepreneurship'], ['Forklift training', 'Machine Operation'], ['Crane training', 'Machine Operation'], ['Reachstacker', 'Machine Operation'], ['SME Digital Boot-camp', 'SME Digital Boot-camp']] as $key) {
            $courses = [
                'name' => $key[0],
                'program' => $key[1],
                'slug' => Str::slug($key[0], '-'),
                'duration' => mt_rand(2, 8) . ' ' . $faker->randomElement(['Hours', 'Days', 'Weeks', 'Months', 'Years']),
                'status' => mt_rand(0, 10) != 0,
                'summary' => $faker->realText(300),
                'banner' => "2023/" . Str::slug($key[0], '-') . '.jpg',
                'description' => "<h3>About Course</h3><p>{$faker->realText(500)}</p><h3>Who is this course for</h3><p>{$faker->realText(400)}</p><h3>Learning Objectives</h3><p>{$faker->realText(300)}</p>",
                'period' => $faker->randomElement(['Day', 'Week', 'Month', 'Year']),
                'cost' => mt_rand(10000, 100000),
                'discounted_cost' => mt_rand(10000, 100000),
                'date_of_commencement' => date("Y-m-d", mt_rand(1610000000, time())),
                'user_id' => $user->id
            ];
            self::create($courses);
        }
    }
}
