<table>
    <thead>
    <tr>
        <th>Name of Unit</th>
        <th>Progress</th>
    </tr>
    </thead>
    <tbody>
        @foreach($results as $result)
            <tr>
                <td>{{ $result['name'] ?? '' }}</td>
                <td>{{ $result['progress'] ?? '' }} %</td>
            </tr>
        @endforeach
    </tbody>
</table>
