<?php

namespace Base\Interfaces;

interface SyntaxHandlerInterface
{
    public function process(string $content, array $data): string;
}
