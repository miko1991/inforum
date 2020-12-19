<?php

namespace Kernel;

class Template {

    static $blocks = array();
    static $styles = array();
    static $contents = array();
    static $js = array();
    static $cache_path = 'Cache/';
    static $cache_enabled = false;
    static $extra_dir = "";
    static $data = array();
    static $page_content = null;

    public static function getTheme() {
        return "light";
    }

    static function setPageContent($content) {
        self::$page_content = $content;
    }

    static function addContents($file, $dir, $block, $column, $data = array()) {
        self::$contents[] = ["html" => file_get_contents($dir.$file), "block" => $block, "column" => $column, "data" => $data];
    }

    static function view($file, $extra_dir = "./Views/", $data = array()) {
        self::$extra_dir = $extra_dir;
        $cached_file = self::cache($file);
        self::$data = array_merge(self::$data, $data);
        extract(self::$data, EXTR_SKIP);
        require $cached_file;
    }

    static function cache($file) {
        if (!file_exists(self::$cache_path)) {
            mkdir(self::$cache_path, 0744);
        }
        $cached_file = self::$cache_path . str_replace(array('/', '.html'), array('_', ''), $file . '.php');
        if (!self::$cache_enabled || !file_exists($cached_file) || filemtime($cached_file) < filemtime(self::$extra_dir.$file)) {
            $code = self::includeMainFile(self::$extra_dir.$file);
            $code = self::compileCode($code);
            file_put_contents($cached_file, '<?php class_exists(\'' . __CLASS__ . '\') or exit; ?>' . PHP_EOL . $code);
        }
        return $cached_file;
    }

    static function clearCache() {
        foreach(glob(self::$cache_path . '*') as $file) {
            unlink($file);
        }
    }

    static function compileCode($code) {
        $code = self::parsePage($code);
        $code = self::compileCSS($code);
        $code = self::compileBlockStyles($code);
        $code = self::compileYieldStyles($code);
        $code = self::compileBlockJs($code);
        $code = self::compileYieldJs($code);
        $code = self::compileBlock($code);
        $code = self::compileYield($code);
        $code = self::compileEscapedEchos($code);
        $code = self::compileEchos($code);
        $code = self::compilePHP($code);
        return $code;
    }



    static function includeMainFile($file) {
        $code = file_get_contents($file);
        $code = self::getRootViews($code);
        $code = self::getExtraViews($code);

        return $code;
    }

    static function getRootViews($code) {
        preg_match_all('/{% ?(extendsRootView|includeRootView) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], self::includeFiles($value[2]), $code);
        }
        $code = preg_replace('/{% ?(extendsRootView|includeRootView) ?\'?(.*?)\'? ?%}/i', '', $code);
        return $code;
    }

    static function getExtraViews($code) {
        preg_match_all('/{% ?(extendsView|includeView) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], self::includeExtraViews(self::$extra_dir, $value[2]), $code);
        }
        $code = preg_replace('/{% ?(extendsView|includeView) ?\'?(.*?)\'? ?%}/i', '', $code);

        return $code;
    }

    static function includeFiles($file) {
        $code = file_get_contents("./Views/".$file);

        preg_match_all('/{% ?(extendsRootView|includeRootView) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], self::includeFiles($value[2]), $code);
        }
        $code = preg_replace('/{% ?(extendsRootView|includeRootView) ?\'?(.*?)\'? ?%}/i', '', $code);

        return $code;
    }

    static function includeExtraViews($dir, $file) {
        $code = file_get_contents($dir.$file);
        preg_match_all('/{% ?(extendsView|includeView) ?\'?(.*?)\'? ?%}/i', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            $code = str_replace($value[0], self::includeExtraViews($dir, $value[2]), $code);
        }
        $code = preg_replace('/{% ?(extendsView|includeView) ?\'?(.*?)\'? ?%}/i', '', $code);
        return $code;
    }

    static function compileCSS($code) {
        return preg_replace('~\{% (theme) (.*?) ?%}~is',
            '<link rel="stylesheet" href="/Themes/<?php echo $2 ?>/base.css">',
            $code);
    }

    static function compilePHP($code) {
        return preg_replace('~\{%\s*(.+?)\s*\%}~is', '<?php $1 ?>', $code);
    }

    static function compileEchos($code) {
        return preg_replace('~\{{\s*(.+?)\s*\}}~is', '<?php echo $1 ?>', $code);
    }

    static function compileEscapedEchos($code) {
        return preg_replace('~\{{{\s*(.+?)\s*\}}}~is', '<?php echo htmlentities($1, ENT_QUOTES, \'UTF-8\') ?>', $code);
    }

    static function compileBlock($code) {
        preg_match_all('/{% ?block ?(.*?) ?%}(.*?){% ?endblock ?%}/is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            if (!array_key_exists($value[1], self::$blocks)) self::$blocks[$value[1]] = '';
            if (strpos($value[2], '@parent') === false) {
                self::$blocks[$value[1]] = $value[2];
            } else {
                self::$blocks[$value[1]] = str_replace('@parent', self::$blocks[$value[1]], $value[2]);
            }
            $code = str_replace($value[0], '', $code);
        }
        return $code;
    }

    static function compileYield($code) {
        foreach(self::$blocks as $block => $value) {
            $code = preg_replace('/{% ?yield ?' . $block . ' ?%}/', $value, $code);
        }
        $code = preg_replace('/{% ?yield ?(.*?) ?%}/i', '', $code);
        return $code;
    }

    static function compileBlockJs($code) {
        preg_match_all('/{% ?js ?%}(.*?){% ?endjs ?%}/is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            self::$js[] = $value[1];
            $code = str_replace($value[0], '', $code);
        }
        return $code;
    }

    static function compileYieldJs($code) {
        $html = "";
        foreach(self::$js as $value) {
            $html .= $value;
        }
        $code = preg_replace('/{% ?jscontent ?%}/', $html, $code);
        return $code;
    }

    static function compileBlockStyles($code) {
        preg_match_all('/{% ?style ?%}(.*?){% ?endstyle ?%}/is', $code, $matches, PREG_SET_ORDER);
        foreach ($matches as $value) {
            self::$styles[] = $value[1];
            $code = str_replace($value[0], '', $code);
        }
        return $code;
    }

    static function compileYieldStyles($code) {
        $html = "";
        foreach(self::$styles as $value) {
            $html .= $value;
        }
        $code = preg_replace('/{% ?stylecontent ?%}/', $html, $code);
        return $code;
    }

    static function loadView($file, $dir, $data = array()) {
        return ["html" => file_get_contents($dir.$file), "data" => $data];
    }

    static function parsePage($code) {
        $html = "";
        if (!is_countable(self::$page_content) || !count(self::$page_content)) return $code;
        foreach (self::$page_content as $key => $array) {
            $html .= "<div class='block'>";
            if (count($array["columns"]) == 1) {
                $width = "100";
            } elseif (count($array["columns"]) == 2) {
                $width = "50";
            } else {
                $width = "33";
            }

            foreach ($array["columns"] as $column) {
                self::$data = array_merge(self::$data, $column["data"]);

                $html .= '<div class="column column-'.$width.'">';
                $html .= $column["html"];
                $html .= '</div>';
            }
            $html .= "</div>";
        }
        $code = preg_replace('/{% ?page ?%}/', $html, $code);
        return $code;
    }
}
