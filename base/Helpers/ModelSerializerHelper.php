<?php
namespace Base\Helpers;

use Base\Interfaces\ModelSerializerHelperInterface;

class ModelSerializerHelper implements ModelSerializerHelperInterface
{
    // Serialize any model data to an array
    public static function serialize($data)
    {
        // Check if $data is an array or Traversable (e.g., Collection or array of models)
        if (is_array($data) || $data instanceof \Traversable) {
            return array_map(
                fn($item) => method_exists($item, "toArray")
                    ? $item->toArray()
                    : $item,
                $data
            );
        }

        // Check if $data is a single model object
        if (is_object($data) && method_exists($data, "toArray")) {
            return $data->toArray();
        }

        // If it's neither a collection nor a model object, return it as is
        return $data;
    }
}
