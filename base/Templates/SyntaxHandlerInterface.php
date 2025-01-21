<?php

namespace Base\Templates;

interface SyntaxHandlerInterface
{
    public function process(string $content, array $data): string;
}
