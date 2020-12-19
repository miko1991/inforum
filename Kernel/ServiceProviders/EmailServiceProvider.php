<?php

namespace Kernel\ServiceProviders;

use Models\Setting;
use PHPMailer\PHPMailer\PHPMailer;

class EmailServiceProvider
{
    public static function sendEmail($to, $subject, $html): bool
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

            $mail->setFrom($from->value, 'Origade');
            $mail->addAddress($to);

            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body = $html;
            $mail->send();

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}
