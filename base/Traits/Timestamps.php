<?php
namespace Base\Traits;

trait Timestamps
{
    /**
     * Automatically set timestamps on save.
     *
     * @return bool
     */
    public function save(): bool
    {
        $timestamp = date("Y-m-d H:i:s");

        if (!isset($this->{$this->key})) {
            // Insert: Set created_at and updated_at
            $this->created_at = $timestamp;
            $this->updated_at = $timestamp;
        } else {
            // Update: Only set updated_at
            $this->updated_at = $timestamp;
        }

        return parent::save();
    }
}
