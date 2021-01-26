<?php

namespace Ss;

spl_autoload_register(function ($class_name) {
    require_once '../' . $class_name . '.php';
});

$redis = new \Redis();
$redis->connect('47.108.148.75', 6379);
$redis->auth('CYPueypzvCAdPh8l');
$redis->select(1);

$redis->watch(['abc', 'ddd']);
sleep(2);
$redis->pipeline()
      ->set('abc', 'ddd')
      ->exec();
