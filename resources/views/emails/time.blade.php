<x-mail::message>
# Timetable

Period: {{ $startDate->toDateString() }} - {{ $endDate->toDateString() }}

@foreach ($timetableEvents as $dayName => $events)
## {{ ucfirst($dayName) }}

@foreach ($events as $event)
- {{ $event['start'] }} - {{ $event['end'] }} | {{ $event['title'] }}
@endforeach

@endforeach

</x-mail::message>