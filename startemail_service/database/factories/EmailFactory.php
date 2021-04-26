<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use Faker\Generator as Faker;
use App\Models\Email;

$factory->define(Email::class, function (Faker $faker) {
    return [
        'name' => 'Takeaway',
        'email' => 'contact@email.com',
        'subject' => 'Email Test',
        'status' => 'delivered'
    ];
});
