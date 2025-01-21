<?php

namespace Base\Templates\SyntaxHandlers;

use Base\Templates\SyntaxHandlerInterface;

class IfHandler implements SyntaxHandlerInterface
{
    public function process(string $content, array $data): string
    {
        return preg_replace(
            [
                "/\{#if\s+(.*?)\}/",
                "/\{:else if\s+(.*?)\}/",
                "/\{:else\}/",
                "/\{\/if\}/",
            ],
            [
                '<?php if ($1): ?>',
                '<?php elseif ($1): ?>',
                "<?php else: ?>",
                "<?php endif; ?>",
            ],
            $content
        );
    }
}
