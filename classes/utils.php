<?php

use Dom\Entity;

class Utils 
{
    public static function leave() 
    {
        header('Location: ..');
    }

     /**
     * Adds HATEOAS links to the data it receives as a parameter
     * 
     * @param array|string $information Entity information to add the HATEOAS links to
     * @param string $entity Name of the entity the HATEOAS links will be added to.
     *                        If nonexistent, only the HATEOAS links will be returned
     * @param array $urlParameters - Contains extra url parameters if present
     * @return string The information to be served by the API including its 
     *          corresponding HATEOAS links encoded as JSON
     */
    public static function addHATEOAS(array|string $information = '', string $entity = '', array $urlParameters = []): string 
    {
        // entity: catalog, author, poem
        // information: authors, info, poems, text

        // $curDir = self::urlPath();

        // if urlParamters is present, then exstract content
        if(!empty($urlParameters)) {
            $author = $urlParameters['author_name'] ?? '';
            $title = $urlParameters['title'] ?? '';
        }

        switch($entity) {
            case 'catalog':
                $self = '/authors';
                break;
            case 'author':
                if($information === 'info') {
                    $self = '/authors/' . $author;
                } else {
                    $self = '/authors/' . $author . '/poems';
                }
                break;
            case 'poem':
                $self = '/authors/' . $author . '/poems/' . $title;
                break;
            default:
                $self = 'undefined';
        }

        $apiInfo['_links'] = array(
            array(
                'self' => $self,
                'type' => 'GET'
            )
        );        
        return json_encode($apiInfo);

        // How I wish the hateoas design should be:
        //authors
        // “_links”: {
        // 	“self”: “/authors”
        // }

        // authors/<author_name>
        // “_links": {
        //     "self": "/authors/<author_name>”,
        //     "all_authors": "/authors",
        //     "poems": "/authors/<author_name>/poems"
        //   }

        // authors/<author_name>/poems
        // "links": {
        //     "self": "/authors/<author_name>/poems",
        //     "all_authors": "/authors",
        //     "author": "/authors/<author_name>"
        //   }

        // authors/<author_name>/poems/<poem_name>
        // "links": {
        //     "self": "/authors/<author_name>/poems/<poem_name>”,
        //     "all_authors": "/authors",
        //     "author": "/authors/<author_name>",
        //     "all_poems": "/authors/<author_name>/poems"
        //   }

    }
      /**
     * Returns the API's URL path
     */
    // private static function urlPath(): string 
    // {
    //     $protocol = ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') 
    //         || $_SERVER['SERVER_PORT'] == 443) ? 'https://' : 'http://';
    //     return $protocol . $_SERVER['HTTP_HOST'] . '/' . 'api' . '/';     
    // }
}