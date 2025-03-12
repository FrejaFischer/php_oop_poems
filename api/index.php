<?php

require_once __DIR__ . '/../classes/utils.php';

if (!isset($_GET['entity']) || !isset($_GET['information'])) {
    Utils::leave();
} else {
    $entity = $_GET['entity'];
    $information = $_GET['information'];

    header('Content-Type: application/json');
    
    switch ($entity) {
        case 'catalog':
            require_once __DIR__ . '/../classes/catalog.php';
            $catalog = new Catalog();
            
            switch ($information) {
                case 'authors':
                    echo json_encode($catalog->getAuthors());
                    break;
                default:
                    Utils::leave();
                }
                break;
        case 'author':
            if (!isset($_GET['name'])) {
                Utils::leave();
            } else {
                require_once __DIR__ . '/../classes/author.php';
                $name = $_GET['name'];
                $name = str_replace('-', ' ', $name);

                $author = new Author($name);
                switch ($information) {
                    case 'info':
                        echo json_encode($author->getInfo());
                        break;
                    case 'poems':
                        echo json_encode($author->getPoems());
                        break;
                    default:
                        Utils::leave();
                }
            }                    
            break;
        case 'poem':
            if (!isset($_GET['authorName']) || !isset($_GET['title'])) {
                Utils::leave();
            } else {
                require_once __DIR__ . '/../classes/poem.php';
                $authorName = $_GET['authorName'];
                $authorName = str_replace('-', ' ', $authorName);
                $title = $_GET['title'];
                // Original title: I heard a Fly buzz - when I died -
                // Title in API request: I-heard-a-Fly-buzz-/-when-I-died-/
                $title = str_replace('-', ' ', $title);
                $title = str_replace('/', '-', $title);
                $poem = new Poem($authorName, $title);
                switch ($information) {
                    case 'text':
                        echo json_encode($poem->getText());
                        break;
                    default: 
                        Utils::leave();
                }
            }
            break;
        default:
            Utils::leave();
    }
}