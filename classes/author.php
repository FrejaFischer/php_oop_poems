<?php

class Author 
{
    private string $name;
    private string $dir;

    public function __construct(string $name)
    {
        $this->name = $name;
        $this->dir = __DIR__ . '/../poems/' . $this->name;
    }
    
    public function getInfo(): array
    {
        $infoFile = $this->dir . '/.info.json';
        if (!file_exists($infoFile)){
            return $this->getAuthorError();
        }
        return json_decode(file_get_contents($infoFile), true);
    } 

    public function getPoems(): array
    {
        if (!is_dir($this->dir)){
            return $this->getAuthorError();
        }
        $poems = scandir($this->dir);
        $poems = array_splice($poems, 3);
        $poems = array_map(function($poemFileName) {
            return substr($poemFileName, 0, strlen($poemFileName) - 5);
        }, $poems);
        return $poems;
    }
    protected function getAuthorError(): array
    {
        http_response_code(404);
            return ['error'=>'Author not found'];
    }
}