<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/3/14
 * Time: 下午4:48
 */

include_once __DIR__ . '/../vendor/autoload.php';

$file = 'user/login.html';

\BaAGee\Template\View::init([
    'sourceViewPath'  => getcwd() . '/view',
    'compileViewPath' => getcwd() . '/compile',
    'isDebug'=>false
]);

// $html = \BaAGee\Template\View::render($file, ['title' => 'userLogin']);
// echo $html;
$s1 = microtime(true);
\BaAGee\Template\View::display($file, ['title' => 'userLogin']);
echo (microtime(true) - $s1) . PHP_EOL;

echo 'over';