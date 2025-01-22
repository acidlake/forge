<?php

namespace Base\Core;

use Base\Adapters\CustomRouter;
use Base\Adapters\MonologAdapter;
use Base\Authentication\Adapters\SmsTwillioAdapter;
use Base\Authentication\JWT\JWT;
use Base\Authentication\OTP\Adapters\EmailAdapter;
use Base\Authentication\OTP\Adapters\GoogleAuthenticatorAdapter;
use Base\Authentication\OTP\OTPManager;
use Base\Database\BaseSchemaBuilder;
use Base\Helpers\EnvHelper;
use Base\Helpers\KeyGenerator;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\ConfigurationManagerInterface;
use Base\Interfaces\JWTInterface;
use Base\Interfaces\JWTMiddlewareInterface;
use Base\Interfaces\KeyGeneratorInterface;
use Base\Interfaces\LoggerInterface;
use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Interfaces\OTPDeliveryAdapterInterface;
use Base\Interfaces\OTPManagerInterface;
use Base\Interfaces\RouterInterface;
use Base\Interfaces\SchemaBuilderInterface;
use Base\ORM\DatabaseAdapter;
use Base\Templates\DefaultViewEngine;
use Base\Tools\ConfigHelper;
use Base\Tools\JWTMiddleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Base\Interfaces\ViewInterface;

/**
 * CoreServiceProvider class responsible for registering core services into the container.
 *
 * @framework Forge
 * @author Jeremias Nunez
 * @github acidlake
 * @license MIT
 * @copyright 2025
 */
class CoreServiceProvider extends ServiceProvider
{
    use ContainerAwareTrait;
    /**
     * Registers core services into the dependency injection container.
     *
     * @param Container $container The dependency injection container instance.
     *
     * @return void
     */
    public function register(Container $container): void
    {
        // Register environment helper
        $container->bind(EnvHelper::class, function () {
            EnvHelper::initialize();
            return new EnvHelper();
        });

        // Register default config helper
        $container->bind(ConfigHelperInterface::class, function () {
            return new ConfigHelper();
        });

        // Register the router
        $container->bind(
            RouterInterface::class,
            AdapterResolver::resolve(
                RouterInterface::class,
                CustomRouter::class,
                "App\\Adapters\\CustomRouter"
            )
        );

        // Register the logger
        $container->bind(LoggerInterface::class, function () {
            // Create a Monolog instance with a StreamHandler
            $monolog = new Logger("app");
            $monolog->pushHandler(
                new StreamHandler(
                    BASE_PATH . EnvHelper::get("LOG_PATH"),
                    Logger::DEBUG
                )
            );

            // Return the MonologAdapter with the Monolog instance
            return new MonologAdapter($monolog);
        });

        // Register default view engine
        $container->bind(ViewInterface::class, function () {
            return new DefaultViewEngine(VIEW_PATH);
        });

        // Register JWT
        $container->bind(JWTInterface::class, function () {
            return new JWT();
        });

        $container->bind(JWTMiddlewareInterface::class, function () {
            /**
             * @var JWTInterface $jwtManager
             */
            $jwtManager = $this->resolve(JWTInterface::class);
            return new JWTMiddleware(jwtManager: $jwtManager);
        });

        // OTP Manager
        $container->bind(OTPManagerInterface::class, function () {
            /**
            @var ConfigHelperInterface $configHelper
            */
            $configHelper = $this->resolve(ConfigHelperInterface::class);
            $config = $configHelper::get("auth");
            return new OTPManager(config: $config);
        });

        // Register the ConfigManager
        $container->bind(ConfigurationManagerInterface::class, function () {
            return new ConfigurationManager(
                CORE_CONFIG_PATH,
                APP_CONFIG_PATH,
                ENV_PATH
            );
        });

        // Register database bindings
        $container->bind(ORMDatabaseAdapterInterface::class, function () {
            $dsn = sprintf(
                "%s:host=%s;dbname=%s",
                EnvHelper::get("DB_CONNECTION", "mysql"),
                EnvHelper::get("DB_HOST", "127.0.0.1"),
                EnvHelper::get("DB_DATABASE", "forge")
            );

            $pdo = new \PDO(
                $dsn,
                EnvHelper::get("DB_USERNAME", "root"),
                EnvHelper::get("DB_PASSWORD", "")
            );
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            return new DatabaseAdapter($pdo);
        });

        // Register SchemaBuilder
        $container->bind(SchemaBuilderInterface::class, function ($container) {
            /**
            @var ORMDatabaseAdapterInterface $adapter
            */
            $adapter = $container->resolve(ORMDatabaseAdapterInterface::class);
            return new BaseSchemaBuilder($adapter);
        });

        // Register KeyGenerator
        $container->bind(KeyGeneratorInterface::class, function () {
            return new KeyGenerator();
        });

        /**
         * OTP Base Adapters
         */
        // Email
        $container->bind(OTPDeliveryAdapterInterface::class, function () {
            return new EmailAdapter();
        });
        // Google
        $container->bind(OTPDeliveryAdapterInterface::class, function () {
            return new GoogleAuthenticatorAdapter();
        });
        // SMS
        $container->bind(OTPDeliveryAdapterInterface::class, function () {
            return new SmsTwillioAdapter();
        });
    }
}
