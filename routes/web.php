<?php

use App\Http\Controllers\DashboardController;
use App\Mail\Timetable;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Http;
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('dashboard', DashboardController::class)->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/mailable', function () {
    $startDate = now()->startOfWeek();
    $endDate = now()->endOfWeek();

    $response = Http::get('https://tahveltp.edu.ee/hois_back/timetableevents/timetableSearch', [
        'from' => $startDate->toIsoString(),
        'thru' => $endDate->toIsoString(),
        'lang' => 'ET',
        'page' => 0,
        'schoolId' => 38,
        'size' => 50,
        'studentGroups' => '39248890-7051-4182-9a9a-8a82259b862b',
    ]);

    $data = $response->json();

    $content = data_get($data, 'content', []);

    $events = [];

    foreach ($content as $event) {
        $rawDate = data_get($event, 'date');

        $date = $rawDate
            ? Carbon::parse($rawDate)->toDateString()
            : null;

        $events[] = [
            'date' => $date,
            'start' => data_get($event, 'timeStart'),
            'end' => data_get($event, 'timeEnd'),
            'title' => data_get($event, 'nameEt')
                ?? data_get($event, 'nameEn')
                ?? data_get($event, 'nameRu'),
        ];
    }

    $timetableEvents = collect($events)
        ->sortBy(['date', 'start'])
        ->groupBy(function ($event) {
            return Carbon::parse($event['date'])
                ->locale('et_EE')
                ->dayName;
        });

    return new Timetable($timetableEvents, $startDate, $endDate);
});

require __DIR__ . '/settings.php';
require __DIR__ . '/auth.php';
require __DIR__ . '/posts.php';
require __DIR__ . '/authors.php';
