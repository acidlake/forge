<?php

namespace Base\Core;

use Base\Adapters\CustomRouter;
use Base\Adapters\MonologAdapter;
use Base\Authentication\Adapters\SmsTwillioAdapter;
use Base\Authentication\JWT\JWT;
use Base\Authentication\OTP\Adapters\EmailAdapter;
use Base\Authentication\OTP\Adapters\GoogleAuthenticatorAdapter;
use Base\Authentication\OTP\OTPManager;
use Base\Controllers\BaseApiController;
use Base\Database\BaseSchemaBuilder;
use Base\Helpers\EnvHelper;
use Base\Helpers\EnvValueParser;
use Base\Helpers\KeyGenerator;
use Base\Helpers\ModelSerializerHelper;
use Base\Interfaces\BaseApiControllerInterface;
use Base\Interfaces\ConfigHelperInterface;
use Base\Interfaces\ConfigurationManagerInterface;
use Base\Interfaces\EnvValueParserInterface;
use Base\Interfaces\JWTInterface;
use Base\Interfaces\JWTMiddlewareInterface;
use Base\Interfaces\KeyGeneratorInterface;
use Base\Interfaces\LoggerInterface;
use Base\Interfaces\ModelSerializerHelperInterface;
use Base\Interfaces\NotificationManagerInterface;
use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Interfaces\OTPDeliveryAdapterInterface;
use Base\Interfaces\OTPManagerInterface;
use Base\Interfaces\RequestInterface;
use Base\Interfaces\ResponseInterface;
use Base\Interfaces\RouterInterface;
use Base\Interfaces\SchemaBuilderInterface;
use Base\Interfaces\StorageManagerInterface;
use Base\Notifications\Drivers\EmailDriver;
use Base\Notifications\Drivers\PushDriver;
use Base\Notifications\Drivers\SMSDriver;
use Base\Notifications\NotificationManager;
use Base\ORM\BaseModelInterface;
use Base\ORM\DatabaseAdapter;
use Base\ORM\OrmManagerInterface;
use Base\Router\Http\Request;
use Base\Router\Http\Response;
use Base\Storage\Drivers\DatabaseStorageDriver;
use Base\Storage\Drivers\FileStorageDriver;
use Base\Storage\Drivers\RedisStorageDriver;
use Base\Templates\DefaultViewEngine;
use Base\Tools\ConfigHelper;
use Base\Tools\JWTMiddleware;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Base\Interfaces\ViewInterface;
use PDO;

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

        $this->registerModels($container);

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

        // Register OrmManager
        $container->bind(OrmManagerInterface::class, function () use (
            $container
        ) {
            $databaseAdapter = $container->resolve(
                ORMDatabaseAdapterInterface::class
            );
            return new \Base\ORM\DefaultOrmManager($databaseAdapter);
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

        // Storage
        $container->bind(StorageManagerInterface::class, function () {
            /**
            @var ConfigHelperInterface $configHelper
            */
            $configHelper = $this->resolve(ConfigHelperInterface::class);
            $configSession = $configHelper::get("storage.session");
            $driver = $configSession["driver"];

            switch ($driver) {
                case "redis":
                // TODO: Implement redis
                // $redis = new Redis();
                // $redis->connect(
                //     env("REDIS_HOST", "127.0.0.1"),
                //     env("REDIS_PORT", 6379)
                // );
                // return new RedisStorageDriver($redis);

                case "database":
                    $pdo = new PDO(
                        EnvHelper::get("DB_DSN"),
                        EnvHelper::get("DB_USERNAME"),
                        EnvHelper::get("DB_PASSWORD")
                    );
                    return new DatabaseStorageDriver($pdo);

                default:
                    return new FileStorageDriver($configSession["path"]);
            }
        });

        // Notification
        $container->bind(NotificationManagerInterface::class, function () {
            /**
            @var ConfigHelperInterface $configHelper
            */
            $configHelper = $this->resolve(ConfigHelperInterface::class);
            $config = $configHelper::get("notifications.channels");

            return new NotificationManager([
                "email" => new EmailDriver(
                    host: $config["email"]["host"],
                    from: $config["email"]["from"],
                    port: $config["email"]["port"],
                    username: $config["email"]["username"],
                    password: $config["email"]["password"],
                    encryption: $config["email"]["encryption"]
                ),
                "sms" => new SMSDriver(
                    $config["sms"]["accountSid"],
                    $config["sms"]["authToken"],
                    $config["sms"]["from"]
                ),
                "push" => new PushDriver($config["push"]["firebaseKey"]),
            ]);
        });

        // Queue Jobs
        //

        // TODO: Refactor to a Framework helper class
        // to avoid adding every helper apart
        $container->bind(EnvValueParserInterface::class, function () {
            return new EnvValueParser();
        });

        // Base API Controller
        $container->bind(BaseApiControllerInterface::class, function () {
            return new BaseApiController();
        });

        // Model Serializer
        $container->bind(ModelSerializerHelperInterface::class, function () {
            return new ModelSerializerHelper();
        });

        // Request
        $container->bind(
            RequestInterface::class,
            AdapterResolver::resolve(
                RequestInterface::class,
                Request::class,
                "App\\Adapters\\Response"
            )
        );

        // Response
        $container->bind(
            ResponseInterface::class,
            AdapterResolver::resolve(
                ResponseInterface::class,
                Response::class,
                "App\\Adapters\\Response"
            )
        );

        // Uuid Strategies
        $container->bind(\Base\Tools\UuidManager::class, function () {
            $manager = new \Base\Tools\UuidManager();

            // Auto-discover user-defined strategies
            foreach (glob(BASE_PATH . "/app/UuidStrategies/*.php") as $file) {
                $className = "App\\UuidStrategies\\" . basename($file, ".php");
                if (
                    class_exists($className) &&
                    is_subclass_of(
                        $className,
                        \Base\Interfaces\UuidStrategyInterface::class
                    )
                ) {
                    $strategy = new $className();
                    $manager->register($strategy->getName(), $strategy);
                }
            }

            return $manager;
        });
    }

    /**
     * Automatically register all models in the app/Models directory.
     *
     * @param Container $container
     * @return void
     */
    private function registerModels(Container $container): void
    {
        $modelsPath = BASE_PATH . "/app/Models";

        if (!is_dir($modelsPath)) {
            return;
        }

        foreach (glob("{$modelsPath}/*.php") as $file) {
            $className = "App\\Models\\" . basename($file, ".php");

            if (
                class_exists($className) &&
                is_subclass_of($className, BaseModelInterface::class)
            ) {
                // Register the model in the container
                $container->bind($className, function () use (
                    $container,
                    $className
                ) {
                    $orm = $container->resolve(OrmManagerInterface::class);
                    $uuidManager = $container->resolve(
                        \Base\Tools\UuidManager::class
                    );

                    return new $className($orm, $uuidManager);
                });
            }
        }
    }
}
