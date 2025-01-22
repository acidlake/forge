<?php

use Base\Helpers\EnvHelper;

return [
    "channels" => [
        "email" => [
            "driver" => "email",
            "from" => EnvHelper::get("MAIL_FROM", "no-reply@forge.com"),
            "host" => EnvHelper::get("MAIL_HOST", "mailpit"),
            "port" => EnvHelper::get("MAIL_PORT", 1025),
            "username" => EnvHelper::get("MAIL_HOST", ""),
            "password" => EnvHelper::get("MAIL_HOST", ""),
            "encryption" => EnvHelper::get("MAIL_ENCRYPTION", "tls"),
        ],
        "sms" => [
            "driver" => "sms",
            "accountSid" => EnvHelper::get(
                "TWILIO_ACCOUNT_SID",
                "twillio_account_sid"
            ),
            "authToken" => EnvHelper::get(
                "TWILIO_AUTH_TOKEN",
                "twillio_aut_token"
            ),
            "from" => EnvHelper::get("TWILIO_FROM", "no-reply@forge.com"),
        ],
        "push" => [
            "driver" => "push",
            "firebaseKey" => EnvHelper::get(
                "FIREBASE_KEY",
                "your-firebase-key"
            ),
        ],
    ],
];
