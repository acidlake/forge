<?php

/**
 * Script to add a new namespace-to-path mapping for the Forge framework's toolbox.
 *
 * This script allows developers to register new namespaces and their corresponding paths
 * in the `namespace_map.php` file, which is used for autoloading classes from the toolbox folder.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */

$namespaceMapFile = __DIR__ . "/namespace_map.php";

/**
 * Load the existing namespace-to-path map.
 *
 * @var array $namespaceMap The associative array of namespace mappings.
 */
$namespaceMap = require $namespaceMapFile;

/**
 * Prompt the user for the namespace and path.
 */
$namespace = readline("Enter the namespace (e.g., 'Psr\\Log'): ");
$path = readline("Enter the path (relative to toolbox/, e.g., 'psr/src'): ");

/**
 * Check if the namespace already exists in the map.
 * If not, add the new namespace-to-path mapping.
 */
if (!isset($namespaceMap[$namespace])) {
    $namespaceMap[$namespace] = $path;

    // Save the updated namespace map back to the file
    file_put_contents(
        $namespaceMapFile,
        "<?php\n\nreturn " . var_export($namespaceMap, true) . ";\n"
    );

    echo "Library added successfully.\n";
} else {
    echo "Namespace already exists in the map.\n";
}
