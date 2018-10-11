<?php

namespace App\Service;

/**
 * Abstract class to support same interface for various document types (CSV, PDF etc.)
 */
abstract class AbstractDocumentGenerator
{
    /**
     * Generate/save document by given data to given path
     *
     * @param string $path Path to save document to (including actual file name)
     * @param array  $data Array which contains rows of named columns (associative sub-arrays) to export
     *
     * @return bool|int The number of bytes that were written to the file, or false on failure.
     */
    abstract public function generateDocument(string $path, array $data);
}
