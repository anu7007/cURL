<?php

use Phalcon\Mvc\Controller;

use GuzzleHttp\Client;

class IndexController extends Controller
{
    public function indexAction()
    {
        if ($this->request->ispost('search')) {
            $name = $this->request->getpost('name');
            $name = str_replace(' ', '+', $name);
            // $url = "https://openlibrary.org/search.json?q=$name&mode=ebooks&has_fulltext=true";

            // // Initialize a CURL session.
            // $ch = curl_init();

            // //grab URL and pass it to the variable.
            // curl_setopt($ch, CURLOPT_URL, $url);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $response = json_decode(curl_exec($ch));
            // $response = json_decode(curl_exec($ch), true);
            // $this->view->response = $response['docs'];
            // $this->view->count = $response->numFound;
            $client = new Client([
                
                'base_uri' => 'https://openlibrary.org/',
            ]);
            
            $response = $client->request('GET', 'search.json?q=' . $name . '&mode=ebooks&has_fulltext=true');
            $response = json_decode($response->getBody(), true);
            $count = $response['numFound'];
            $this->view->response = $response['docs'];
            $this->view->count = $response['numFound'];
            if ($count == 0) {
                $this->view->NRF = "No results found!!";
            }
            $this->view->count = "$count search results";
        }
    }
    public function detailsAction()
    {
        $id = $this->request->get('id');
        $img = $this->request->get('img');
        // $url = "https://openlibrary.org/api/books?bibkeys=ISBN:$id&jscmd=details&format=json";
        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $client = new Client([
                
            'base_uri' => 'https://openlibrary.org/',
        ]);
        
        $response = $client->request('GET', 'api/books?bibkeys=ISBN:'.$id.'&jscmd=details&format=json');
        $response = json_decode($response->getBody(), true);
        $this->view->response = $response['ISBN:' . $id . '']['details'];
        $this->view->img = $img;
        $this->view->googleID = $response['ISBN:' . $id . '']['details']['identifiers']['google'][0];
    }
}
