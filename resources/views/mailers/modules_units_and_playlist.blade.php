<br><br>
<ul>
    @if(isset($units) && !empty($units))
        @foreach($units as $unit)
            <li>
                <a href="{{ config('app.url')  }}client/lms/unit/{{ $unit->id }}" style="color: inherit; border-style: none; text-decoration: none !important; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;" border="0">{{ $unit->name }}</a>
            </li>
        @endforeach
    @endif
    @if(isset($modules) && !empty($modules))
        @foreach($modules as $module)
            <li>
                <a href="{{ config('app.url')  }}client/lms/module/{{ $module->id }}" style="color: inherit; border-style: none; text-decoration: none !important; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;" border="0">{{ $module->name }}</a>
            </li>
        @endforeach
    @endif
    @if(isset($playlists) && !empty($playlists))
        @foreach($playlists as $playlist)
            <li>
                <a href="{{ config('app.url')  }}playlist/{{ $playlist->id }}" style="color: inherit; border-style: none; text-decoration: none !important; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;" border="0">{{ $playlist->name }}</a>
            </li>
        @endforeach
    @endif
</ul>
<br>
<br> 