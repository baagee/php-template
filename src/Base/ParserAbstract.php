<?php
/**
 * Desc: 视图解析base
 * User: baagee
 * Date: 2019/3/14
 * Time: 下午5:26
 */

namespace BaAGee\Template\Base;

/**
 * 视图解析base
 * Class ParserBase
 * @package BaAGee\Template\View
 */
abstract class ParserAbstract
{
    use ProhibitNewClone;

    /**
     * @var array 标签
     */
    protected static $tagMap = [];
    /**
     * @var bool 是否初始化
     */
    protected static $isInit = false;

    /**
     * @param array $tagMap
     */
    public static function init(array $tagMap)
    {
        if (static::$isInit === false) {
            if (!empty($tagMap)) {
                static::$tagMap = array_merge(static::$tagMap, $tagMap);
            }
            static::$isInit = true;
        }
    }

    /**
     * @param string $htmlStr
     * @return mixed
     */
    abstract public static function parse(string $htmlStr);
}
