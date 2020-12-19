<?php


namespace Controllers;

use Exception;
use Kernel\Authentication;
use Kernel\Response;
use Kernel\Template;
use Kernel\Updater;
use Models\Setting;
use PHPMailer\PHPMailer\PHPMailer;

class AdminController
{
    public function index()
    {
        $version = Updater::getVersion();
        Template::view("AdminDashboard.html", "./Views/", [
            "version" => $version
        ]);
    }

    public function sendTestEmail()
    {
        $host = Setting::where(["prop" => "smtp_host"])->first();
        $username = Setting::where(["prop" => "smtp_username"])->first();
        $password = Setting::where(["prop" => "smtp_password"])->first();
        $from = Setting::where(["prop" => "smtp_from"])->first();

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = $host->value;
            $mail->SMTPAuth = true;
            $mail->Username = $username->value;
            $mail->Password = $password->value;
            $mail->Port = 587;

            $mail->setFrom($from->value, 'Test Email From Origade');
            $mail->addAddress($_POST["test_email_to"]);

            $mail->isHTML(true);
            $mail->Subject = 'Here is the subject';
            $mail->Body    = 'This is the HTML message body <b>in bold!</b>';
            $mail->send();
        } catch (\Exception $e) {
            Response::json(["error" => $e->ErrorInfo]);
        }
    }
}
