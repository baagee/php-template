<?php
/**
 * Desc:
 * User: baagee
 * Date: 2019/3/14
 * Time: 下午4:48
 */

include_once __DIR__ . '/../vendor/autoload.php';

$file = 'user/login.tpl';

\BaAGee\Template\View::init([
    'sourceViewPath'  => getcwd() . '/view',
    'compileViewPath' => getcwd() . '/compile',
    'isDebug'         => true,
    'tagMap'          => [
        // 自定义模板标签 正则表达式=>替换的内容
        '/abc/' => 'ABC'
    ]
]);

$data=[
    'title' => 'userLogin',
    'info'=>[
        'name'=>'小明',
        'age'=>12,
        'sex'=>'男'
    ],
    'time'=>time()
];
define('APP_Name','app_nameee');
$html = \BaAGee\Template\View::render($file, $data);
echo $html;
$s1 = microtime(true);
\BaAGee\Template\View::display($file, $data);
echo (microtime(true) - $s1) . PHP_EOL;

echo 'over';
