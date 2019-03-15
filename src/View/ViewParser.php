<?php
/**
 * Desc: 视图解析
 * User: baagee
 * Date: 2019/3/14
 * Time: 下午4:10
 */

namespace BaAGee\Template\View;

use BaAGee\Template\View;

/**
 * 内置的模板解析类
 * Class ViewParser
 * @package BaAGee\Template\View
 */
class ViewParser extends ParserBase
{
    /**
     * @param array $tagMap
     */
    public static function init(array $tagMap=[])
    {
        // 直接读取内置tagMap
        self::$tagMap = include_once implode(DIRECTORY_SEPARATOR, [__DIR__, 'tagsMap.php']);
        parent::init($tagMap);
    }

    /**
     * @param $htmlStr
     * @return string
     * @throws \Exception
     */
    public static function parse(string $htmlStr)
    {
        if (self::$isInit === false) {
            throw new \Exception('视图解析没有初始化：ViewParser::init');
        }
        // 检测有没有填坑
        preg_match('/{{layout\s+(.+?)}}/', $htmlStr, $match);
        if (!empty($match[1])) {
            // 获取布局文件内容
            $layout_html = file_get_contents(View::getSourceFilePath($match[1]));
            // 检测子页面使用了父页面的哪些块 获取所有坑
            preg_match_all('/{{hole\s+(.+?)}}/', $layout_html, $holes);
            if (!empty($holes[1])) {
                // 父亲存在坑
                foreach ($holes[1] as $hole_name) {
                    // 提取儿子模板的 坑对应内容
                    $pattern = '/{{fill\s+' . $hole_name . '}}(.+?){{end\s+' . $hole_name . '}}/s';
                    preg_match($pattern, $htmlStr, $hole_contents);
                    if (!empty($hole_contents[1])) {
                        // 提取到了 替换父模板 坑为子模板的真实内容
                        $replacement = $hole_contents[1];
                    } else {
                        // 去除父模板 没有用到的block
                        $replacement = '';
                    }
                    $layout_html = preg_replace('/{{hole\s+' . $hole_name . '}}/s', $replacement, $layout_html);
                }
            }
            return self::parse($layout_html);
        } else {
            // 查看有没有include其他模板
            preg_match_all('/{{include\s+(.+?)}}/', $htmlStr, $includes);
            if (!empty($includes[1])) {
                foreach ($includes[1] as $k => $include) {
                    $include_html = file_get_contents(View::getSourceFilePath($include));
                    $htmlStr      = str_replace($includes[0][$k], self::parse($include_html), $htmlStr);
                }
            }
            // 没有引用布局 退出递归
            $pattern     = array_keys(self::$tagMap);
            $replacement = array_values(self::$tagMap);
            return preg_replace($pattern, $replacement, $htmlStr);
        }
    }
}
