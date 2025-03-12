<?php

class Poem 
{
    private string $author;
    private string $title;

    public function __construct(string $author, string $title)
    {
        $this->author = $author;
        $this->title = $title;
    }
    
    public function getText(): array
    {
        if(!is_dir(__DIR__ . '/../poems/' . $this->author)){
            http_response_code(404);
            return ['error'=>'Author not found'];
        }
        $poemFile = __DIR__ . '/../poems/' . $this->author . '/' . $this->title . '.json';
    if (!file_exists($poemFile)) {
        http_response_code(404);
            return ['error'=>'Poem not found'];
    }
        return json_decode(file_get_contents($poemFile), true);
    } 
}