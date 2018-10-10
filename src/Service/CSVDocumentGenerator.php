<?php

namespace App\Service;

use Symfony\Component\Serializer\SerializerInterface;

class CSVDocumentGenerator extends DocumentGenerator
{
    private $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * Generate/save CSV document by given data to given path
     *
     * @param string $path
     * @param array $data
     */
    public function generateDocument(string $path, array $data): void
    {
        file_put_contents(
            $path,
            $this->serializer->encode($data, 'csv')
        );
    }
}