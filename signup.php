<?php

namespace ramenApp;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use ramenApp\Bootstrap;
use ramenApp\lib\PDODatabase;
use ramenApp\lib\Signup;
use ramenApp\lib\Session;

$loader = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loader, [
    'cache' => Bootstrap::CACHE_DIR
]);

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_NAME, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::LOG_PATH);
$signup =  new Signup($db);
$ses = new Session();

$context = [];

if (!empty($_POST)) {
    if (!empty($_POST['user_name'])) {
        $user_name = $_POST['user_name'];
    } else {
        $context['name_err'] = '名前を入力してください';
    };

    if (!empty($_POST['pass'])) {
        $pass = $_POST['pass'];
    } else {
        $context['emptypass'] = 'パスワードを入力してください';
    }
};


if (!empty($user_name) && !empty($pass)) {
    if ($signup->signup($user_name, $pass)) {
        header('Location:top.php');
        exit();
    } else {
        $context['name_err'] = 'その名前はすでに登録されています';
    }
}


$template = $twig->loadTemplate('signup.html.twig');
$template->display($context);