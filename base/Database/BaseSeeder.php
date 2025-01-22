<?php
namespace Base\Database;

use Base\Core\ContainerHelper;
use Base\Interfaces\ORMDatabaseAdapterInterface;
use Base\Interfaces\SeederInterface;

abstract class BaseSeeder implements SeederInterface
{
    /**
     * Seed the database with data.
     */
    abstract public function run(): void;

    /**
     * Call another seeder class.
     *
     * @param string $seederClass The fully qualified class name of the seeder to call.
     */
    protected function call(string $seederClass): void
    {
        $seeder = new $seederClass();
        if ($seeder instanceof BaseSeeder) {
            $seeder->run();
        } else {
            throw new \RuntimeException(
                "Class {$seederClass} is not a valid seeder."
            );
        }
    }

    /**
     * Access the database adapter directly for queries.
     */
    protected function db(): ORMDatabaseAdapterInterface
    {
        return ContainerHelper::getContainer()->resolve(
            ORMDatabaseAdapterInterface::class
        );
    }

    /**
     * Seed the database using a model.
     *
     * @param string $modelClass Fully qualified class name of the model.
     * @param array $records Array of data for multiple rows (object-like structure).
     */
    protected function seedModel(string $modelClass, array $records): void
    {
        /** @var ModelInterface $model */
        $model = new $modelClass();

        foreach ($records as $record) {
            // Validate and fill defaults using the model's schema
            $record = $model->validateAndFillDefaults($record);

            // Insert the record into the database
            $this->db()->save($model->getTableName(), [$record]);
        }

        echo "Seeded data into " . $model->getTableName() . "\n";
    }
}
