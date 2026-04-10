<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $defaultCity = (string) config('services.weather.default_city', 'Kuressaare');
        $defaultCountry = trim((string) config('services.weather.default_country', ''));

        $city = trim((string) $request->query('city', $defaultCity));
        $country = strtoupper(trim((string) $request->query('country', $defaultCountry)));

        if ($city === '') {
            $city = $defaultCity;
        }

        $weather = [
            'data' => null,
            'error' => null,
            'search' => [
                'city' => $city,
                'country' => $country,
            ],
        ];

        $apiKey = (string) config('services.weather.key');

        if ($apiKey === '') {
            $weather['error'] = 'Weather API key is missing. Set WEATHER_API in your environment.';
        } else {
            $cacheKey = sprintf(
                'weather:%s:%s',
                $this->normalizeForCache($city),
                $country !== '' ? $this->normalizeForCache($country) : 'none'
            );

            $cachedWeather = Cache::get($cacheKey);

            if (is_array($cachedWeather)) {
                $weather['data'] = $cachedWeather;
            } else {
                $query = $country !== '' ? "{$city},{$country}" : $city;

                try {
                    $response = Http::timeout(10)->get('https://api.openweathermap.org/data/2.5/weather', [
                        'q' => $query,
                        'appid' => $apiKey,
                        'units' => 'metric',
                    ]);

                    if ($response->successful()) {
                        $weatherData = $response->json();

                        $formattedWeather = [
                            'city' => data_get($weatherData, 'name', $city),
                            'country' => data_get($weatherData, 'sys.country', $country),
                            'temperature' => data_get($weatherData, 'main.temp'),
                            'description' => data_get($weatherData, 'weather.0.description'),
                            'humidity' => data_get($weatherData, 'main.humidity'),
                            'wind' => data_get($weatherData, 'wind.speed'),
                            'icon' => data_get($weatherData, 'weather.0.icon'),
                        ];

                        Cache::put($cacheKey, $formattedWeather, now()->addHour());

                        $weather['data'] = $formattedWeather;
                    } elseif ($response->status() === 404) {
                        $weather['error'] = 'Weather was not found for that location. Try another city or country code.';
                    } else {
                        $weather['error'] = 'Weather service is unavailable right now. Please try again later.';
                    }
                } catch (\Throwable) {
                    $weather['error'] = 'Weather service is unavailable right now. Please try again later.';
                }
            }
        }

        return Inertia::render('Dashboard', [
            'weather' => $weather,
        ]);
    }

    private function normalizeForCache(string $value): string
    {
        $normalized = preg_replace('/\s+/', '-', mb_strtolower(trim($value)));

        return $normalized !== null && $normalized !== '' ? $normalized : 'default';
    }
}
