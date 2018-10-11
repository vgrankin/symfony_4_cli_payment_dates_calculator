<?php

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;

class CSVDocumentGenerator extends AbstractDocumentGenerator
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Generate/save CSV document by given data to given path
     *
     * @param string $path Path to save document to (including actual file name)
     * @param array  $data Array which contains rows of named columns (associative sub-arrays) to export to CSV
     *
     * @return bool|int The number of bytes that were written to the file, or false on failure.
     */
    public function generateDocument(string $path, array $data)
    {
        try {
            return file_put_contents(
                $path,
                $this->serializer->encode($data, 'csv')
            );
        } catch (\Exception $e) {
            return false;
        }
    }
}