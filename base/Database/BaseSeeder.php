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
     * Call one or multiple seeder classes.
     *
     * @param string|array $seederClasses Fully qualified class name(s) of the seeder(s) to call.
     */
    protected function call(string|array $seederClasses): void
    {
        $seederClasses = is_array($seederClasses)
            ? $seederClasses
            : [$seederClasses];

        foreach ($seederClasses as $seederClass) {
            echo "Calling seeder: {$seederClass}\n";
            $seeder = new $seederClass($this->db());
            if ($seeder instanceof SeederInterface) {
                $seeder->run();
            } else {
                throw new \RuntimeException(
                    "Class {$seederClass} is not a valid seeder."
                );
            }
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
     * @param array $records Array of data for multiple rows.
     */
    protected function seedModel(string $modelClass, array $records): void
    {
        $model = new $modelClass();

        foreach ($records as $record) {
            $model->save($record);
        }

        echo "Seeded data into " . $model->getTableName() . "\n";
    }

    /**
     * Rollback data for a model.
     *
     * @param string $modelClass Fully qualified class name of the model.
     * @param array $conditions Conditions for rollback.
     */
    protected function rollbackModel(
        string $modelClass,
        array $conditions
    ): void {
        echo "Rolling back data in " . $modelClass::getTableName() . "...\n";
        $this->db()->delete($modelClass::getTableName(), $conditions);
    }

    /**
     * Call rollback on one or multiple seeders.
     *
     * @param array $seeders List of seeder class names.
     */
    protected function callRollback(array $seeders): void
    {
        foreach (array_reverse($seeders) as $seederClass) {
            echo "Rolling back seeder: {$seederClass}\n";

            $seeder = new $seederClass($this->db());
            if (method_exists($seeder, "rollback")) {
                $seeder->rollback();
            }
        }
    }
}
