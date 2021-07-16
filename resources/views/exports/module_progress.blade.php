<table>
    <thead>
    <tr>
        <th>Name of Unit</th>
        <th>From Module/Playlist Name</th>
        <th>Type</th>
        <th>Status</th>
        <th>Date Completed</th>
        <th>Played</th>
        <th>Has Note</th>
    </tr>
    </thead>
    <tbody>

        @foreach($user['units'] as $unit)
            <tr>
                <td>{{ $unit['name'] ?? '' }}</td>
                <td>{{ $unit['module'] ?? '' }}</td>
                <td>{{ $unit['type'] ?? '' }}</td>
                <td>{{ $unit['status'] ?? '' }}</td>
                <td>{{ $unit['date_completed'] ?? '' }}</td>
                <td>{{ $unit['played'] ?? '' }}</td>
                <td>{{ $unit['hasNote'] ?? '' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
