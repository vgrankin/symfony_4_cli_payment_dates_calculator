<?php

namespace App\Service;
/**
 * Abstract class to support same interface for various document types (CSV, PDF etc.)
 */
abstract class DocumentGenerator
{
    public abstract function generateDocument(string $path, array $data);
}