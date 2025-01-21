<?php

$namespaceMapFile = __DIR__ . "/namespace_map.php";
$namespaceMap = require $namespaceMapFile;

$namespace = readline("Enter the namespace (e.g., 'Psr\\Log'): ");
$path = readline("Enter the path (relative to toolbox/, e.g., 'psr/src'): ");

if (!isset($namespaceMap[$namespace])) {
    $namespaceMap[$namespace] = $path;
    file_put_contents(
        $namespaceMapFile,
        "<?php\n\nreturn " . var_export($namespaceMap, true) . ";\n"
    );
    echo "Library added successfully.\n";
} else {
    echo "Namespace already exists in the map.\n";
}
