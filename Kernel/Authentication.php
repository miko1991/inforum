<?php


namespace Kernel;

use Kernel\Kernel;
use Helpers\Helpers;
use Kernel\ServiceProviders\EmailServiceProvider;
use Models\Session;
use Models\Setting;
use Models\User;
use PDO;

class Authentication
{
    public static $user;

    public static function sendRandomToken(Model $user): void {
        $code = rand(1000, 9999);

        $html = <<<EOF
        <h1>Welcome To Origade</h1>
        <h3>Here is your Two-Factor Authentication Code</h3>
        <h2>{$code}</h2>
        EOF;
        
        EmailServiceProvider::sendEmail($user->email, "Your Two-Factor Authenication Code", $html);
        $user->token = $code;
        $user->save();
    }

    public static function checkTwoFactorAuthenticationEnabled()
    {
        $setting = Setting::where(["prop" => "auth_enable_two_factor"])->first();
        if (!$setting) return false;
        return $setting->value == 1;
    }

    public static function createSession($user)
    {
        $expiry = date('y:m:d', time() + 60 * 60 * 24 * 365);

        $session = new Session();
        $session->sessid = Helpers::generateRandomString(64);
        $session->user_id = $user->id;
        $session->expiry = $expiry;
        $session->save();

        setcookie("sessid", $session->sessid, time() + 60 * 60 * 24 * 365, "/");
    }

    public static function user()
    {
        if (!isset($_COOKIE["sessid"])) {
            return false;
        }

        if (self::$user) return self::$user;

        //$stmt = Kernel::$db->prepare("SELECT * FROM users WHERE id = ?");
        //$stmt->execute([$_SESSION["user_id"]]);
        //$result = $stmt->fetch(PDO::FETCH_OBJ);

        $session = Session::where(["sessid" => $_COOKIE["sessid"]])->with(["user.group.permissionSet"])->first();
        if (!$session) {
            return false;
        }

        $user = $session->user;

        if (!$user) {
            return false;
        }

        self::$user = $user;
        return $user;
    }

    public static function check($password, $user): bool
    {
        if (!password_verify($password, $user->password)) {
            return false;
        }

        return true;
    }

    public static function login($user)
    {
        self::createSession($user);
        self::$user = $user;
    }

    public static function createUser($display_name, $email, $password)
    {
        $hash = Helpers::generateHash($password);
        $stmt = Kernel::$db->prepare("INSERT INTO users (displayName, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$display_name, $email, $hash]);
    }

    public static function logout()
    {
        Session::delete()->where(["sessid" => $_COOKIE["sessid"]])->exec();
        setcookie("sessid", "", time() - 3600, "/");
    }
}
