<?php

class Catalog 
{
    public function getAuthors(): array
    {
        $authors = scandir(__DIR__ . '/../poems');
        return array_splice($authors, 2);
    }
}