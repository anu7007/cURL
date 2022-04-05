<?php

use Phalcon\Mvc\Controller;

class IndexController extends Controller
{
    public function indexAction()
    {
        if ($this->request->ispost('search')) {
            $name = $this->request->getpost('name');
            $name = str_replace(' ', '+', $name);
            // $temp = explode(" ", $name);
            // $item = implode("+", $temp);
            //   die($name);
            $url = "https://openlibrary.org/search.json?q=$name&mode=ebooks&has_fulltext=true";

            // Initialize a CURL session.
            $ch = curl_init();

            //grab URL and pass it to the variable.
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch));
            $count = $response->numFound;
            $response = json_decode(curl_exec($ch), true);
            $this->view->response = $response['docs'];
            $this->view->count = $response->numFound;
            // $this->view->author_name = $response->docs[0]->author_name;
            // $this->view->title = $response->docs[0]->title;
            // $this->view->FPY = $response->docs[0]->first_publish_year;
            if ($count == 0) {
                $this->view->NRF = "No results found!!";
            }
            $this->view->count = "$count search results";
            // die;
        }
    }
    public function detailsAction()
    {
        $id = $this->request->get('id');
        $img = $this->request->get('img');
        $url = "https://openlibrary.org/api/books?bibkeys=ISBN:$id&jscmd=details&format=json";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        $this->view->response = $response['ISBN:'.$id.'']['details'];
        $this->view->img=$img;
        $this->view->googleID = $response['ISBN:'.$id.'']['details']['identifiers']['google'][0];
      

    }
}
