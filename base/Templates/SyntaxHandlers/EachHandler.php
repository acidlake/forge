<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Interfaces\SyntaxHandlerInterface;

class EachHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        // Handle {#each} blocks with {:noitems}
        $content = preg_replace_callback(
            '/\{#each\s+([\$\w\->\[\]\'"]+)\s+as\s+(\$\w+)(?:,\s*(\$\w+))?\}(.*?)\{:noitems\}(.*?)\{\/each\}/s',
            function ($matches) {
                $collection = $matches[1];
                $item = $matches[2];
                $index = $matches[3] ?? null;
                $loopContent = $matches[4];
                $noItemsContent = $matches[5];

                $phpCode = "<?php if (!empty({$collection})): ?>";
                $phpCode .=
                    "<?php foreach ({$collection} as " .
                    ($index ? "{$index} => " : "") .
                    "{$item}): ?>";
                $phpCode .= str_replace(
                    ["{{", "}}"],
                    ["<?= ", "; ?>"],
                    $loopContent
                );
                $phpCode .= "<?php endforeach; ?>";
                $phpCode .= "<?php else: ?>";
                $phpCode .= $noItemsContent;
                $phpCode .= "<?php endif; ?>";

                return $phpCode;
            },
            $content
        );

        // Handle {#each} blocks without fallback
        $content = preg_replace_callback(
            '/\{#each\s+([\$\w\->\[\]\'"]+)\s+as\s+(\$\w+)(?:,\s*(\$\w+))?\}(.*?)\{\/each\}/s',
            function ($matches) {
                $collection = $matches[1];
                $item = $matches[2];
                $index = $matches[3] ?? null;
                $loopContent = $matches[4];

                $phpCode = "<?php if (!empty({$collection})): ?>";
                $phpCode .=
                    "<?php foreach ({$collection} as " .
                    ($index ? "{$index} => " : "") .
                    "{$item}): ?>";
                $phpCode .= str_replace(
                    ["{{", "}}"],
                    ["<?= ", "; ?>"],
                    $loopContent
                );
                $phpCode .= "<?php endforeach; ?>";
                $phpCode .= "<?php endif; ?>";

                return $phpCode;
            },
            $content
        );

        return $content;
    }
}
