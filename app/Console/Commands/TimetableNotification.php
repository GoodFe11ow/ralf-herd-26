<?php

namespace App\Console\Commands;

use App\Mail\Timetable;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Carbon;
use Ramsey\Collection\Collection;

class TimetableNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:timetable-notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
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
    }
}
