<?php

return [
    ["group_title" => "Email", "group_description" => "Here you can edit email settings", "settings" => [
        ["prop" => "smtp_host", "title" => "SMTP Host"],
        ["prop" => "smtp_username", "title" => "SMTP Username"],
        ["prop" => "smtp_password", "title" => "SMTP Password"],
        ["prop" => "smtp_from", "title" => "SMTP From"],
    ]],
    ["group_title" => "Authentication", "group_description" => "Here you can edit authentication settings", "settings" => [
        ["prop" => "auth_enable_two_factor", "title" => "Enable Two-Factor Authentication?"]
    ]]
];
