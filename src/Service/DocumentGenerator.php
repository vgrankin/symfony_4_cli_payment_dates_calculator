<?php

namespace App\Service;

abstract class DocumentGenerator
{
    public abstract function generateDocument($path, array $data);

    protected abstract function saveDocument($path);
}