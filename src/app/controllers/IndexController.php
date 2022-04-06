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
            $url = "http://api.weatherapi.com/v1/search.json?q='.$name.'&key=0bab7dd1bacc418689b143833220304";

            // Initialize a CURL session.
            $ch = curl_init($url);

            //grab URL and pass it to the variable.
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch));
            $response = json_decode(curl_exec($ch), true);
            // echo "<pre>";
            // print_r($response);
            // die;
            $this->view->response = $response;
            $this->view->count = count($response) . ' search results';


            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////

            // $client = new Client([

            //     'base_uri' => 'https://openlibrary.org/',
            // ]);

            // $response = $client->request('GET', 'search.json?q=' . $name . '&mode=ebooks&has_fulltext=true');
            // $response = json_decode($response->getBody(), true);
            // $count = $response['numFound'];
            // $this->view->response = $response['docs'];
            // $this->view->count = $response['numFound'];
            // if ($count == 0) {
            //     $this->view->NRF = "No results found!!";
            // }
            // $this->view->count = "$count search results";
        }
    }
    public function detailsAction()
    {
        $id = $this->request->get('id');
        $id = str_replace('-', '+', $id);
        $url = "http://api.weatherapi.com/v1/current.json?q='.$id.'&key=0bab7dd1bacc418689b143833220304";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = json_decode(curl_exec($ch), true);
        $this->view->id = $id;
        $this->view->response = $response;
        
        if($this->request->getPost('currentweather')){
            // die('currentweather');
            $this->view->selected="Current Weather";
            $url = "http://api.weatherapi.com/v1/current.json?q='.$id.'&key=0bab7dd1bacc418689b143833220304";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);
            $this->view->answer = array(
                'Wind Speed'=>$response['current']['wind_kph'].'km/h',
                'Wind Direction'=>$response['current']['wind_dir'],
                'Humidity'=>$response['current']['humidity'],
                'Feels Like'=>$response['current']['feelslike_c'].'&degC'
        );
        }
        
        elseif($this->request->getPost('forecast')){
            $this->view->selected="ForeCast";
            $day=$this->request->get('day');
            $url = "http://api.weatherapi.com/v1/forecast.json?q='.$id.'&day='.$day.'&key=0bab7dd1bacc418689b143833220304";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);
            $this->view->answer = array(
                'Minimum Temperature'=>$response['forecast']['forecastday'][0]['day']['mintemp_c'].'&degC',
                'Average Temperature'=>$response['forecast']['forecastday'][0]['day']['avgtemp_c'].'&degC',
                'Maximum Temperature'=>$response['forecast']['forecastday'][0]['day']['maxtemp_c'].'&degC',
                
            );
        }
        elseif($this->request->getPost('history')){
            $this->view->selected="History";

        }
        elseif($this->request->getPost('timezone')){
            $this->view->selected="Time Zone";
            $url = "http://api.weatherapi.com/v1/timezone.json?q='.$id.'&key=0bab7dd1bacc418689b143833220304";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);
           
            $this->view->answer = array(
                'Time Zone'=>$response['location']['tz_id'],
                'Localtime Epoch'=>$response['location']['localtime_epoch'],
                'Current Localtime'=>$response['location']['localtime'],
                
            );
        }
        elseif($this->request->getPost('sports')){
            $this->view->selected="Sports";
            $url = "http://api.weatherapi.com/v1/sports.json?q='.$id.'&key=0bab7dd1bacc418689b143833220304";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);
            $this->view->answer = array(
                'Stadium'=>$response['football'][0]['stadium'],
                'Country'=>$response['football'][0]['country'],
                'Tournament'=>$response['football'][0]['tournament'],
                'Start'=>$response['football'][0]['start'],
                'Match'=>$response['football'][0]['match'],

               
                
            );
        }
        elseif($this->request->getPost('astronomy')){
            $this->view->selected="Astronomy";
            $url = "http://api.weatherapi.com/v1/astronomy.json?q='.$id.'&key=0bab7dd1bacc418689b143833220304";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);
             
            $this->view->answer = array(
                'Sunrise'=>$response['astronomy']['astro']['sunrise'],
                'Sunset'=>$response['astronomy']['astro']['sunset'],  
                'Moonrise'=>$response['astronomy']['astro']['moonrise'], 
                'Moonset'=>$response['astronomy']['astro']['moonset'],
                'Moon Phase'=>$response['astronomy']['astro']['moon_phase'],
                'Moon Illumination'=>$response['astronomy']['astro']['moon_illumination']
            );
        }
        elseif($this->request->getPost('weatheralerts')){
            $this->view->selected="Weather Alerts";
        }
        elseif($this->request->getPost('aqi')){ //air quality
            $this->view->selected="Air Quality";
            $url = "http://api.weatherapi.com/v1/current.json?q='.$id.'&key=0bab7dd1bacc418689b143833220304&aqi=yes";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = json_decode(curl_exec($ch), true);
            
            $this->view->answer = array(
                'CO'=>$response['current']['air_quality']['co'],
                'NO2'=>$response['current']['air_quality']['no2'],
                'O3'=>$response['current']['air_quality']['o3'],
                'SO2'=>$response['current']['air_quality']['so2'],
                'PM2_5'=>$response['current']['air_quality']['pm2_5'],


        );
        }

    }
   


}
