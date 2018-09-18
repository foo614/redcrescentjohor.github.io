<?php

use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'body' => $faker->text(200),
        'status' => 1,
        'post_type_id' => 2
    ];
});
