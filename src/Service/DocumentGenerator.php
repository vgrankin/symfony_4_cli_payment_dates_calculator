<?php

namespace App\Service;

abstract class DocumentGenerator
{
    public abstract function generateDocument(string $path, array $data): void;
}