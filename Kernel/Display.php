<?php


namespace Kernel;


class Display
{
    public static function with($name = "", $values = [])
    {
        if (!empty($name)) {
            if (count($values)) {
                if (!empty($_SESSION[$name])) {
                    unset($_SESSION[$name]);
                }
                $_SESSION[$name] = json_encode($values);
            }
            elseif (!count($values) && !empty($_SESSION[$name])) {
                $return = json_decode($_SESSION[$name]);
                unset($_SESSION[$name]);
                return $return;
            }
        }
    }

    public static function flash($name = "", $message = "")
    {
        if (!empty($name)) {
            if (!empty($message)) {
                if (!empty($_SESSION[$name])) {
                    unset($_SESSION[$name]);
                }
                $_SESSION[$name] = json_encode($message);
            }
            elseif (empty($message) && !empty($_SESSION[$name])) {
                $return = json_decode($_SESSION[$name]);
                unset($_SESSION[$name]);
                return $return;
            }
        }
    }
}