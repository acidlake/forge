<?php

use Base\Helpers\EnvHelper;

return [
    "default_adapter" => "session", // Can be swapped to 'token', 'custom', etc.

    "password_hashing" => [
        "algorithm" => PASSWORD_BCRYPT,
        "options" => ["cost" => 12],
    ],

    "session" => [
        "lifetime" => 120, // minutes
        "secure" => true, // Use secure cookies
    ],

    "permissions" => [
        "roles" => [
            "admin" => ["*"], // Admin has all permissions
            "editor" => ["edit_post", "delete_post"],
            "viewer" => ["view_post"],
        ],
        "default_role" => "viewer",
    ],

    "jwt" => [
        "secret" => EnvHelper::get("JWT_SECRET", "super_secret_key"),
        "algorithm" => "HS256",
        "expiration" => 3600, // 1 hour
    ],
    "refresh_tokens" => [
        "enabled" => true,
        "lifetime" => 604800, // 7 days
    ],
    "otp" => [
        "length" => EnvHelper::get("OTP_CODE_LENGTH", 6),
        "delivery" => EnvHelper::get("OTP_DELIVERY_METHOD", "email"), // 'google_auth', 'sms', etc.
    ],
];
