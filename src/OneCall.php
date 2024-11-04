<?php

namespace Minhyung\OpenWeather;

use DateTimeInterface;
use GuzzleHttp\Client;

class OneCall
{
    const VERSION = '3.0';

    private ?Client $client = null;

    public function __construct(
        private string $apiKey
    ) {
        //
    }

    /**
     * Current and forecasts weather data
     * 
     * @link   https://openweathermap.org/api/one-call-3#current
     * 
     * @param  float  $lat  Latitude (-90; 90)
     * @param  float  $lon  Longitude (-180; 180)
     * @param  array  $exclude  Excluded parts from the API response. ('current', 'minutely', 'hourly', 'daily', 'alerts)
     * @param  string|null  $units  Units of measurement. ('standard', 'metric', 'imperial')
     * @param  string|null  $lang   Output language
     * @return array
     */
    public function forecast(
        float $lat,
        float $lon,
        array $exclude = [],
        ?string $units = null,
        ?string $lang = null
    ) {
        $params = [];

        $params['lat'] = $lat;
        $params['lon'] = $lon;

        if ($exclude) {
            $params['exclude'] = implode(',', $exclude);
        }

        if ($units) {
            $params['units'] = $units;
        }

        if ($lang) {
            $params['lang'] = $lang;
        }

        return $this->request('onecall', $params);
    }

    /**
     * Weather data for timestamp
     * 
     * @link   https://openweathermap.org/api/one-call-3#history
     * 
     * @param  float  $lat  Latitude (-90; 90)
     * @param  float  $lon  Longitude (-180; 180)
     * @param  int    $dt   Timestamp (Unix time, UTC time zone)
     * @param  string|null  $units  Units of measurement. ('standard', 'metric', 'imperial')
     * @param  string|null  $lang   Output language
     * @return array
     */
    public function timemachine(
        float $lat,
        float $lon,
        int $dt,
        ?string $units = null,
        ?string $lang = null
    ) {
        $params = [];

        $params['lat'] = $lat;
        $params['lon'] = $lon;
        $params['dt'] = $dt;

        if ($units) {
            $params['units'] = $units;
        }

        if ($lang) {
            $params['lang'] = $lang;
        }

        return $this->request('onecall/timemachine', $params);
    }

    /**
     * Daily aggregation
     * 
     * @link   https://openweathermap.org/api/one-call-3#history_daily_aggregation
     * 
     * @param  float  $lat  Latitude (-90; 90)
     * @param  float  $lon  Longitude (-180; 180)
     * @param  string|\DateTimeInterface  $date   'YYYY-MM-DD' format string or DateTimeInterface
     * @param  string|null  $units  Units of measurement. ('standard', 'metric', 'imperial')
     * @param  string|null  $lang   Output language
     */
    public function day_summary(
        float $lat,
        float $lon,
        $date,
        ?string $units = null,
        ?string $lang = null
    ) {
        $params = [];

        $params['lat'] = $lat;
        $params['lon'] = $lon;
        $params['date'] = ($date instanceof DateTimeInterface) ? $date->format('Y-m-d') : $date;

        if ($units) {
            $params['units'] = $units;
        }

        if ($lang) {
            $params['lang'] = $lang;
        }

        return $this->request('onecall/day_summary', $params);
    }

    /**
     * Weather overview
     * 
     * @link   https://openweathermap.org/api/one-call-3#weather_overview
     * 
     * @param  float  $lat  Latitude (-90; 90)
     * @param  float  $lon  Longitude (-180; 180)
     * @param  string|\DateTimeInterface|null  $date   'YYYY-MM-DD' format string or DateTimeInterface
     * @param  string|null  $units  Units of measurement. ('standard', 'metric', 'imperial')
     */
    public function overview(
        float $lat,
        float $lon,
        $date = null,
        ?string $units = null
    ) {
        $params = [];

        $params['lat'] = $lat;
        $params['lon'] = $lon;

        if ($date) {
            $params['date'] = ($date instanceof DateTimeInterface) ? $date->format('Y-m-d') : $date;
        }

        if ($units) {
            $params['units'] = $units;
        }

        return $this->request('onecall/overview', $params);
    }

    protected function request(string $uri, array $params = [])
    {
        $this->client ??= new Client([
            'base_uri' => 'https://api.openweathermap.org/data/'.self::VERSION.'/',
        ]);

        $response = $this->client->get($uri, [
            'query' => array_merge($params, ['appid' => $this->apiKey]),
        ]);
        
        return json_decode((string) $response->getBody(), true);
    }
}
