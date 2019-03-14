<?php
/**
 * Desc: View接口
 * User: baagee
 * Date: 2019/3/14
 * Time: 下午5:14
 */

namespace BaAGee\Template\View;

interface ViewInterface
{
    public static function render(string $source, array $data = []);

    public static function display(string $source, array $data = []);
}
