<?php
error_reporting(1);
ini_set('display_errors', 1);

require_once __DIR__.'/../vendor/autoload.php';
include __DIR__.'/config/config.php';

$app = new Silex\Application();

//default definitions
$app->get('/', function () use ($blogPosts) {
    return '<h3>Welcome to Event Handler </h3>';
});

//register database connection
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'   => DB_DRIVER,
        'host' => DB_HOST,
        'dbname'=> DB_NAME,
        'user' => DB_USER,
        'password' => DB_PASSWORD,
        'charset' => DB_CHARSET
    ),
));

//load the "callback" controller
$app->mount('/callback', include __DIR__.'/controllers/callback.php');

$app['debug'] = true;

$app->run();