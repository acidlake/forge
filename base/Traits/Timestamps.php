<?php
namespace Base\Traits;

use DateTime;

trait Timestamps
{
    protected bool $usesTimestamps = true;

    public function save(array $data): object
    {
        $now = (new DateTime())->format("Y-m-d H:i:s");

        if ($this->usesTimestamps) {
            if (!isset($data["id"])) {
                $data["created_at"] = $now;
            }
            $data["updated_at"] = $now;
        }

        return parent::save($data);
    }
}
