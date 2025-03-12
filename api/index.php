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
                    echo Utils::addHATEOAS($information,$entity);
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
                $formattedName = str_replace('-', ' ', $name);

                $author = new Author($formattedName);
                switch ($information) {
                    case 'info':
                        echo json_encode($author->getInfo());
                        echo Utils::addHATEOAS($information,$entity,['author_name'=>$name]);
                        break;
                    case 'poems':
                        echo json_encode($author->getPoems());
                        echo Utils::addHATEOAS($information,$entity,['author_name'=>$name]);
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
                $name = $_GET['authorName'];
                $formattedName = str_replace('-', ' ', $name);
                $title = $_GET['title'];
                // Original title: I heard a Fly buzz - when I died -
                // Title in API request: I-heard-a-Fly-buzz-/-when-I-died-/
                $formattedTitle = str_replace('-', ' ', $title);
                $formattedTitle = str_replace('/', '-', $formattedTitle);
                $poem = new Poem($formattedName, $formattedTitle);
                switch ($information) {
                    case 'text':
                        echo json_encode($poem->getText());
                        echo Utils::addHATEOAS($information,$entity,['title'=>$title, 'author_name'=>$name]);
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