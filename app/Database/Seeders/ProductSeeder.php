<?php

namespace App\Seeders;

use Base\Interfaces\SeederInterface;
use Base\Database\DatabaseAdapterInterface;

class ProductSeeder implements SeederInterface
{
    public function __construct(protected DatabaseAdapterInterface $db) {}

    public function run(): void
    {
        echo "Seeding products with raw SQL...\n";

        // Advanced approach: Use raw SQL
        $this->db->execute("
            INSERT INTO products (id, name, price) VALUES
            (1, 'Product A', 9.99),
            (2, 'Product B', 19.99)
        ");
    }

    public function rollback(): void
    {
        echo "Rolling back products...\n";

        // Rollback: Raw SQL
        $this->db->execute("DELETE FROM products WHERE id IN (1, 2)");
    }
}
