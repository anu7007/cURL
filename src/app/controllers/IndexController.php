<?php

use Phalcon\Mvc\Controller;

use GuzzleHttp\Client;

class IndexController extends Controller
{
    public function indexAction()
    {
        $client = new Client([

            'base_uri' => 'http://api.weatherapi.com/v1/',
        ]);
        if ($this->request->ispost('search')) {
            //using guzzle->
            $name = $this->request->getpost('name');
            $name = str_replace('-', '+', $name);
            $response = $client->request('GET', 'search.json?q=' . $name . '&key=0bab7dd1bacc418689b143833220304');
            $response = json_decode($response->getBody(), true);

            //using cURL->

            // $url = "http://api.weatherapi.com/v1/search.json?q='.$name.'&key=0bab7dd1bacc418689b143833220304";

            // // Initialize a CURL session.
            // $ch = curl_init($url);

            // //grab URL and pass it to the variable.
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $response = json_decode(curl_exec($ch));
            // $response = json_decode(curl_exec($ch), true);
            // echo "<pre>";
            // print_r($response);
            // die;
            $this->view->response = $response;
            $this->view->count = count($response) . ' search results';


            /////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        }
    }
    public function detailsAction()
    {
        $id = $this->request->get('id');
        $id = str_replace('-', '+', $id);
        $client = new Client([

            'base_uri' => 'http://api.weatherapi.com/v1/',
        ]);
        $name = $this->request->getpost('name');
        $name = str_replace('-', '+', $name);
        $response = $client->request('GET', 'current.json?q=' . $id . '&key=0bab7dd1bacc418689b143833220304');
        $response = json_decode($response->getBody(), true);
        $this->view->response = $response;

        if ($this->request->getPost('currentweather')) {
            $this->view->selected = "Current Weather";
            $response = $client->request('GET', 'current.json?q=' . $id . '&key=0bab7dd1bacc418689b143833220304');
            $response = json_decode($response->getBody(), true);
            $this->view->answer = array(
                'Wind Speed' => $response['current']['wind_kph'] . 'km/h',
                'Wind Direction' => $response['current']['wind_dir'],
                'Humidity' => $response['current']['humidity'],
                'Feels Like' => $response['current']['feelslike_c'] . '&degC'
            );
        } elseif ($this->request->getPost('forecast')) {
            $this->view->selected = "ForeCast";
            $day = $this->request->get('day');
            $response = $client->request('GET', 'forecast.json?q=' . $id . '&key=0bab7dd1bacc418689b143833220304');
            $response = json_decode($response->getBody(), true);
            $this->view->answer = array(
                'Minimum Temperature' => $response['forecast']['forecastday'][0]['day']['mintemp_c'] . '&degC',
                'Average Temperature' => $response['forecast']['forecastday'][0]['day']['avgtemp_c'] . '&degC',
                'Maximum Temperature' => $response['forecast']['forecastday'][0]['day']['maxtemp_c'] . '&degC',

            );
        } elseif ($this->request->getPost('history')) {
            $this->view->selected = "History";
        } elseif ($this->request->getPost('timezone')) {
            $this->view->selected = "Time Zone";
            $response = $client->request('GET', 'timezone.json?q=' . $id . '&key=0bab7dd1bacc418689b143833220304');
            $response = json_decode($response->getBody(), true);

            $this->view->answer = array(
                'Time Zone' => $response['location']['tz_id'],
                'Localtime Epoch' => $response['location']['localtime_epoch'],
                'Current Localtime' => $response['location']['localtime'],

            );
        } elseif ($this->request->getPost('sports')) {
            $this->view->selected = "Sports";
            $response = $client->request('GET', 'sports.json?q=' . $id . '&key=0bab7dd1bacc418689b143833220304');
            $response = json_decode($response->getBody(), true);
            $this->view->answer = array(
                'Stadium' => $response['golf'][0]['stadium'],
                'Country' => $response['golf'][0]['country'],
                'Tournament' => $response['golf'][0]['tournament'],
                'Start' => $response['golf'][0]['start'],
                'Match' => $response['golf'][0]['match'],



            );
        } elseif ($this->request->getPost('astronomy')) {
            $this->view->selected = "Astronomy";
            $response = $client->request('GET', 'astronomy.json?q=' . $id . '&key=0bab7dd1bacc418689b143833220304');
            $response = json_decode($response->getBody(), true);


            $this->view->answer = array(
                'Sunrise' => $response['astronomy']['astro']['sunrise'],
                'Sunset' => $response['astronomy']['astro']['sunset'],
                'Moonrise' => $response['astronomy']['astro']['moonrise'],
                'Moonset' => $response['astronomy']['astro']['moonset'],
                'Moon Phase' => $response['astronomy']['astro']['moon_phase'],
                'Moon Illumination' => $response['astronomy']['astro']['moon_illumination']
            );
        } elseif ($this->request->getPost('weatheralerts')) {
            $this->view->selected = "Weather Alerts";
        } elseif ($this->request->getPost('aqi')) { //air quality
            $this->view->selected = "Air Quality";
            $response = $client->request('GET', 'current.json?q=' . $id . '&key=0bab7dd1bacc418689b143833220304&aqi=yes');
            $response = json_decode($response->getBody(), true);

            $this->view->answer = array(
                'CO' => $response['current']['air_quality']['co'],
                'NO2' => $response['current']['air_quality']['no2'],
                'O3' => $response['current']['air_quality']['o3'],
                'SO2' => $response['current']['air_quality']['so2'],
                'PM2_5' => $response['current']['air_quality']['pm2_5'],
            );
        }
    }
}
