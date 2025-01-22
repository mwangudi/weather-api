<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WeatherController extends Controller 
{
    public function getWeather(Request $request)
    {
        // Validate City is request
        $city = $request->query('city');
        if (!$city) {
            return response()->json(['error' => 'City is required'], 400);
        }

        try {
            // Get coordinates using Geocoding API
            $geoResponse = Http::get("http://api.openweathermap.org/geo/1.0/direct", [
                'q' => $city,
                'limit' => 1,
                'appid' => env('OPENWEATHER_API_KEY')
            ]);

            $geoData = $geoResponse->json();
            if (empty($geoData)) {
                return response()->json(['error' => 'City not found'], 404);
            }

            $lat = $geoData[0]['lat'];
            $lon = $geoData[0]['lon'];

            // Get weather data using coordinates
            $weatherResponse = Http::get("https://api.openweathermap.org/data/2.5/forecast", [
                'lat' => $lat,
                'lon' => $lon,
                'appid' => env('OPENWEATHER_API_KEY'),
                'units' => 'metric'
            ]);

            return response()->json($weatherResponse->json());
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to fetch weather data'], 500);
        }
    }

    public function getWeatherDetails(Request $request)
    {
        // Mock response for wind and humidity details
        return response()->json([
            'wind' => '5.4 m/s',
            'humidity' => '78%'
        ]);
    }
}
