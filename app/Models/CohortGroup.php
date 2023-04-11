<?php

namespace App\Models;

use App\Models\CohortGroup as ModelsCohortGroup;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class CohortGroup extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description'];

    public static function seedGroup()
    {
        self::truncate();
        DB::table('cohort_group_users')->truncate();
        for ($i = 1; $i < mt_rand(2, 18); $i++) {
            $group = null;
            $group['name'] = "Cohort Group {$i}";
            self::create($group);
        }
        $groups = array_column(self::selectRaw('id as cohort_group_id')->get()->toArray(), 'cohort_group_id');
        $users_split =  User::selectRaw('id as user_id')->inRandomOrder()->get()->split(count($groups));
        for ($i = 0; $i < count($groups); $i++) {
            $chunk = $users_split[$i];
            foreach ($chunk as $key) {
                $key['cohort_group_id'] = $groups[$i];
            }
            CohortGroupUsers::insert($chunk->toArray());
        }
    }
}
