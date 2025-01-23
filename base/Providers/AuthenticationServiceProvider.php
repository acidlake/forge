<?php
namespace Base\Providers;

use Base\Authentication\Adapters\SmsTwillioAdapter;
use Base\Authentication\JWT\JWT;
use Base\Authentication\OTP\OTPManager;
use Base\Authentication\OTP\Adapters\EmailAdapter;
use Base\Authentication\OTP\Adapters\GoogleAuthenticatorAdapter;
use Base\Core\Container;
use Base\Core\ContainerAwareTrait;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\JWTInterface;
use Base\Interfaces\JWTMiddlewareInterface;
use Base\Interfaces\OTPDeliveryAdapterInterface;
use Base\Interfaces\OTPManagerInterface;
use Base\Interfaces\ProviderInterface;
use Base\Tools\JWTMiddleware;

class AuthenticationServiceProvider implements ProviderInterface
{
    use ContainerAwareTrait;

    public function register(Container $container): void
    {
        $container->bind(JWTInterface::class, function () {
            return new JWT();
        });

        $container->bind(JWTMiddlewareInterface::class, function ($container) {
            $jwtManager = $container->resolve(JWTInterface::class);
            return new JWTMiddleware(jwtManager: $jwtManager);
        });

        $container->bind(OTPManagerInterface::class, function () {
            /**
            @var ConfigHelperInterface $configHelper
            */
            $configHelper = $this->resolve(ConfigHelperInterface::class);
            $config = $configHelper::get("auth");
            return new OTPManager(config: $config);
        });

        $container->bind(OTPManager::class, function ($container) {
            $configHelper = $container->resolve(ConfigHelperInterface::class);
            $config = $configHelper::get("auth");
            return new OTPManager(config: $config);
        });

        $container->bind(OTPDeliveryAdapterInterface::class, function () {
            return new EmailAdapter();
        });

        $container->bind(OTPDeliveryAdapterInterface::class, function () {
            return new GoogleAuthenticatorAdapter();
        });

        $container->bind(OTPDeliveryAdapterInterface::class, function () {
            return new SmsTwillioAdapter();
        });
    }
}
