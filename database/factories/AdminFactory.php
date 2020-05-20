<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use Faker\Generator as Faker;
use Illuminate\Support\Str;
use Techneved\Admin\Login\Models\Admin;


$factory->define(Admin::class, function (Faker $faker) {
    return [
        'first_name'        => $faker->firstName,
        'last_name'         => $faker->lastName,
        'admin_id'          => $faker->userName,
        'email'             => $faker->unique()->safeEmail,
        'email_verified_at' => null,
        'password'          => 'password',
        'mobile'            => $faker->phoneNumber,
        'remember_token'    => Str::random(10),
        'status'            => 1
    ];
});
