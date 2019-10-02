<?php

namespace ramenApp;

require_once dirname(__FILE__) . '/Bootstrap.class.php';

use ramenApp\Bootstrap;
use ramenApp\lib\PDODatabase;
use ramenApp\lib\Top;
use ramenApp\lib\Session;

$db = new PDODatabase(Bootstrap::DB_HOST, Bootstrap::DB_NAME, Bootstrap::DB_USER, Bootstrap::DB_PASS, Bootstrap::LOG_PATH);
$top = new Top($db);
$ses = new Session();

$loade = new \Twig_Loader_Filesystem(Bootstrap::TEMPLATE_DIR);
$twig = new \Twig_Environment($loade, [
    'cache' => Bootstrap::CACHE_DIR
]);

if (!empty($_POST['search'])) {
    $searchText = $_POST['search'];
    $searchSwitch = $_POST['ANDOR'];
    $context['dataArr'] = $top->searchData($searchText, $searchSwitch);
} else {
    $context['dataArr'] = $top->getData();
}

(!empty($_SESSION['user_id'])) ? $context['session'] = $_SESSION : '';

$template = $twig->loadTemplate('top.html.twig');
$template->display($context);
