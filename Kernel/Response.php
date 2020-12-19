<?php


namespace Kernel;
use Kernel\Model;

class Response
{
    public static function expectsJson(): bool {
        if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')
        {
            return true;
        }

        return false;
    }

    public static function json($contents) {
        if (is_array($contents) && count($contents)) {
            $contents = self::parseArray($contents);
        } elseif($contents instanceof Model) {
            $contents = self::parseModel($contents);
        }

        print json_encode($contents);
    }

    private static function parseArray($array) {
        if (is_array($array) && !count($array)) {
            return [];
        } elseif (is_array($array) && count($array)) {
            foreach ($array as &$content) {
                if (is_array($content) && count($content)) {
                    $content = self::parseArray($content);
                } else if ($content instanceof Model) {
                    $content = self::parseModel($content);
                }
            }
        }

        return $array;
    }

    private static function parseModel($model) {
        $parsed = [];
        foreach ($model->attributes as $key => $value) {
            if ($value instanceof Model) {
                $parsed[$key] = self::parseModel($value);
            } elseif (is_array($value)) {
                $parsed[$key] = self::parseArray($value);
            } else {
                $parsed[$key] = $value;
            }
        }
        return $parsed;
    }
}