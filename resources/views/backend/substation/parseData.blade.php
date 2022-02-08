@foreach ($data as $key => $voltageLevel)
<li>{{ $key }}
    <ul>
        @foreach ($voltageLevel as $rtid => $busbarSection)
        <li>
            {{ $busbarSection['busbursection']['name'] }}
            <ol>
                @foreach ($busbarSection['terminals'] as $terminal)
                <li>{{ $terminal['name'] }} ({{ $terminal['busBarConnectionDotNumber']}})</li>
                @endforeach
            </ol>
        </li>
        @endforeach
    </ul>
</li>
@endforeach
