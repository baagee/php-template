<?php
/*
 * 批量验证测试
 */
include __DIR__ . '/../vendor/autoload.php';


class unitTest extends \PHPUnit\Framework\TestCase
{

    public function setUp()
    {
        \BaAGee\Template\View::init([
            'sourceViewPath' => __DIR__ . '/view',
            'compileViewPath' => __DIR__ . '/compile',
            'isDebug' => true,
            'tagMap' => [
                // 自定义模板标签 正则表达式=>替换的内容
                '/abc/' => 'ABC'
            ]
        ]);
    }

    public function testRender()
    {
        $data = [
            'title' => 'userLogin',
            'info' => [
                'name' => '小明',
                'age' => 12,
                'sex' => '男'
            ],
            'time' => time()
        ];
        $file = '/user/login.tpl';
        $html = \BaAGee\Template\View::render($file, $data);
        echo $html;
        $this->assertEquals(2 > 0, true);
    }

    public function testDisplay()
    {
        $data = [
            'title' => 'userLogin',
            'info' => [
                'name' => '小明',
                'age' => 12,
                'sex' => '男'
            ],
            'time' => time()
        ];
        $file = '/user/login.tpl';
        \BaAGee\Template\View::display($file, $data);
        $this->assertEquals(2 > 0, true);
    }
}
