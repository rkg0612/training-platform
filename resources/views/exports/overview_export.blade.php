<table>
    <thead>
    <tr>
        <th>Module Name</th>
        <th>Progress</th>
    </tr>
    </thead>
    <tbody>
        @foreach($results as $i => $result)
            @if ($loop->first) @continue @endif
            <tr>
                <td>{{ $i ?? '' }}</td>
                <td>{{ $result ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
