<?php

/**
 * Namespace-to-path mapping for the Forge framework toolbox.
 *
 * This file provides an associative array that maps PHP namespaces to their corresponding
 * directory paths within the `toolbox/` folder. These mappings are used for autoloading
 * classes dynamically.
 *
 * To add a new mapping, use the toolbox script or manually add entries here.
 *
 * @framework Forge
 * @license MIT
 * @github acidlake
 * @author Jeremias
 * @copyright 2025
 */

return [
    "Psr\\Log" => "psr/src", // PSR Log library
    "Monolog" => "monolog/src/Monolog", // Monolog logging library
    "App" => "app/src", // Application-specific namespace
];
