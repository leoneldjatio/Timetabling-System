<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Student::class, function (Faker $faker) {

    return [
        'student_name' => $faker->name,
        'matricule_no' => $faker->unique()->safeEmail,
        'student_type' => $faker->name,
        'departments_department_id' => random_int(1,16),
        'roles_role_id' => 2
    ];
});


$factory->define(App\Teacher::class, function (Faker $faker) {


    return [
        'teacher_name' => $faker->name,
        'teacher_type' => $faker->name,
        'roles_role_id' => 4,
        'teacher_status' => random_int(0,1),
        


    ];
});
