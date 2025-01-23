<?php
namespace Base\Helpers;

use Base\Interfaces\ModelSerializerHelperInterface;

/**
 * ModelSerializerHelper
 *
 * A utility class to serialize model data (single model or collections of models)
 * into arrays, making it easier to work with and transform data in a standardized format.
 */
class ModelSerializerHelper implements ModelSerializerHelperInterface
{
    /**
     * Serialize model data into an array.
     *
     * This method checks if the input data is a single model object or an array/collection of models.
     * If it's a model, it calls the `toArray()` method to convert it to an array.
     * If it's a collection of models, it applies the same logic for each item in the collection.
     *
     * @param mixed $data The data to serialize (can be a single model or a collection).
     * @return mixed The serialized data in array form, or the data as-is if it cannot be serialized.
     */
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
