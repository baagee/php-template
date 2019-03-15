<?php
/**
 * Desc: 视图类
 * User: baagee
 * Date: 2019/3/14
 * Time: 下午4:09
 */

namespace BaAGee\Template;

use BaAGee\Template\View\ParserBase;
use BaAGee\Template\View\ProhibitNewClone;
use BaAGee\Template\View\ViewInterface;
use BaAGee\Template\View\ViewParser;

/**
 * Class View
 * @package BaAGee\Template
 */
class View implements ViewInterface
{
    use ProhibitNewClone;
    /**
     * @var bool
     */
    protected static $isInit = false;

    /**
     * @var array
     */
    protected static $config = [
        // 是否开发模式
        'isDebug'         => true,
        // 模板文件基本目录
        'sourceViewPath'  => '',
        // 编译后的模板文件目录
        'compileViewPath' => '',
        // 模板解析类
        'viewParser'      => ViewParser::class
    ];

    /**
     * @param array $config
     * @throws \Exception
     */
    public static function init(array $config = [])
    {
        if (self::$isInit === false) {
            $config   = self::checkConfig($config);
            $myTagMap = $config['tagMap'];
            unset($config['tagMap']);
            self::$config = array_merge(self::$config, $config);
            call_user_func(self::$config['viewParser'] . '::init', $myTagMap);
            self::$isInit = true;
        }
    }

    /**
     * @param array $config
     * @return array
     * @throws \Exception
     */
    private static function checkConfig(array $config)
    {
        if (empty($config)) {
            throw new \Exception('视图模板配置不能为空');
        }
        if (empty($config['sourceViewPath'])) {
            throw new \Exception('缺少配置 sourceViewPath');
        } else {
            $config['sourceViewPath'] = rtrim($config['sourceViewPath'], DIRECTORY_SEPARATOR);
        }
        if (empty($config['compileViewPath'])) {
            throw new \Exception('缺少配置 compileViewPath');
        } else {
            $config['compileViewPath'] = rtrim($config['compileViewPath'], DIRECTORY_SEPARATOR);
        }
        if (isset($config['viewParser']) && !empty($config['viewParser'])) {
            if (!is_subclass_of($config['viewParser'], ParserBase::class)) {
                throw new \Exception($config['viewParser'] . '没有继承' . ParserBase::class);
            }
        }
        if (!empty($config['tagMap'])) {
            if (!is_array($config['tagMap'])) {
                throw new \Exception('配置tagMap值不是数组结构');
            }
        } else {
            $config['tagMap'] = [];
        }
        return $config;
    }

    /**
     * 模板渲染
     * @param string $source 模板文件
     * @param array  $data   模板需要的数据
     * @return string
     * @throws \Exception
     */
    public static function render(string $source, array $data = [])
    {
        if (self::$isInit === false) {
            throw new \Exception('视图没有初始化：View::init');
        }
        if (!is_array($data) && ($data == null || $data == '' || empty($data))) {
            $data = [];
        }
        $compile = self::getCompileFilePath($source);
        $source  = self::getSourceFilePath($source);
        if (is_file($source)) {
            if (self::$config['isDebug'] || !is_file($compile) || filemtime($source) > filemtime($compile)) {
                //view目录上次访问的时间大于当前模板
                // fileatime(self::$config['sourceViewPath']) > filemtime($compile)
                self::templateCompile($source, $compile);
            }
            extract($data, EXTR_SKIP);
            ob_start();
            ob_implicit_flush(0);
            include $compile;
            $content = ob_get_clean();
            return $content;
        } else {
            throw new \Exception($source . ' 模板文件不存在');
        }
    }

    /**
     * 显示模板
     * @param string $source
     * @param array  $data
     * @throws \Exception
     */
    public static function display(string $source, array $data = [])
    {
        echo self::render($source, $data);
    }

    /**
     * @param $sourceFile
     * @return string
     */
    public static function getSourceFilePath($sourceFile)
    {
        return self::$config['sourceViewPath'] . DIRECTORY_SEPARATOR . ltrim($sourceFile, DIRECTORY_SEPARATOR);
    }

    /**
     * 获取编译后的文件路径
     * @param $sourceFile
     * @return string
     */
    private static function getCompileFilePath($sourceFile)
    {
        $path = dirname(ltrim($sourceFile, DIRECTORY_SEPARATOR));
        if ($path == '.') {
            $path = '';
        }
        $fileName = basename($sourceFile, '.html') . '.tpl.php';
        return implode(DIRECTORY_SEPARATOR, array_filter([self::$config['compileViewPath'], $path, $fileName]));
    }

    /**
     * 模板编译
     * @param string $from 模板文件路径
     * @param string $to   编译后的文件路径
     * @throws \Exception
     */
    private static function templateCompile($from, $to)
    {
        $path = dirname($to);
        if (!is_dir($path)) {
            mkdir($path, 0777, true);
        }
        ini_set('pcre.backtrack_limit', 99999999);
        ini_set('pcre.recursion_limit', 99999999);
        file_put_contents($to, call_user_func(self::$config['viewParser'] . '::parse', file_get_contents($from)));
    }
}
