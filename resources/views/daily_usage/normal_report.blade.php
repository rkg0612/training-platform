<table style="text-align: center;">
    <thead>
    <tr style="text-align: center; font-weight: 700; font-size: 14px; font-family: helvetica; background: #333333;">
        <th>Name of User</th>
        <th>Last Completed Video</th>
        <th>Total Completed Videos</th>
        <th>Last Unit Marked as Completed</th>
        <th>Total No. of Units Marked as Completed</th>
        <th>No. of Units Marked as Completed This Week</th>
        <th>Last Module Video Played</th>
        <th>Last Played Video of the Day</th>
        <th>Assigned Module with Progress</th>
        <th>Assigned Playlist with Progress</th>
    </tr>
    </thead>
    <tbody>
    @foreach(collect($users)->sortBy('last_video_completed_at', SORT_REGULAR, 'ascending')->toArray() as $user)
        <tr style="text-align:center; font-weight: 400; font-size: 12px; font-family: helvetica;">
            <td>{{ $user['name'] }}</td>
            <td>{{ $user['last_video_played'] }}</td>
            <td>{{ $user['total_videos_played'] }}</td>
            <td>{{ $user['last_video_completed'] }}</td>
            <td>
                {{ $user['totalNumberOfUnitsCompleted'] }}
            </td>
            <td>
                {{ $user['numberOfUnitsCompleted'] }}
            </td>
            <td>{{ $user['last_module_video_played'] }}</td>
            <td>{{ $user['lastWatchedVideoOfTheDayTitle'] }}</td>
            <td>
                <ul style="margin:0;padding:0">
                    @forelse ($user['assignedModules'] as $assignedModules)
                        <li>{{ $assignedModules['name'] }} - {{ $assignedModules['progress'] }}</li>
                    @empty
                    @endforelse
                </ul>
            </td>
            <td>
                <ul style="margin:0;padding:0">
                    @forelse ($user['assignedPlaylists'] as $assignedPlaylist)
                        <li>{{ $assignedPlaylist['name'] }} - {{ $assignedPlaylist['progress'] }}</li>
                    @empty
                    @endforelse
                </ul>
            </td>
        </tr>
    @endforeach
    </tbody>
</table>
