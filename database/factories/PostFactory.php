<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'body' => $faker->sentence,
        'status' => 1,
        'post_type_id' => 1
    ];
});
