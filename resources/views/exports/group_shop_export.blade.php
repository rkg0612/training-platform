<table>
    <tbody>
        <tr>
            <td>Group Shop Name</td>
            <td>{{ $groupShop->name }}</td>
        </tr>
        <tr>
            <td>Date Created</td>
            <td>{{ $groupShop->created_at->format('m-d-Y') }}</td>
        </tr>
        <tr>
            <td>Link to Group Shop</td>
            <td>
                {{ route('index', [ 'any' => '/guest/group-shop/' . $groupShop->guest_view_id ]) }}
            </td>
        </tr>
    </tbody>
</table>


<table>
    <tr></tr>
    <tr></tr>
    <tr></tr>
</table>


@if(count($groupShop->internetShops))
    <table>
        <thead>
        <tr>
            <th>Name of Company</th>
            <th>Name of Specific Dealer</th>
            <th>Is Competitor</th>
            <th>Timezone</th>
            <th>City</th>
            <th>State</th>
            <th>Zip Code</th>
            <th>Shop Type</th>
            <th>Lead Source</th>
            <th>Source's Link</th>
            <th>VIN</th>
            <th>Make</th>
            <th>Model</th>
            <th>Name of SecretShopper</th>
            <th>Lead's Name</th>
            <th>Lead's Email</th>
            <th>Lead's Phone Number</th>
            <th>Name of Salesperson</th>
            <th>Start Date</th>
            <th>Shop Duration</th>
            <th>First Call Received(mm/dd/yy)</th>
            <th>Call Response Time</th>
            <th>Call Attempts</th>
            <th>First SMS Received(mm/dd/yy)</th>
            <th>SMS Response Time</th>
            <th>SMS Attempts</th>
            <th>First Email Received(mm/dd/yy)</th>
            <th>Email Response Time</th>
            <th>Second Email Received(mm/dd/yy)</th>
            <th>Email Attempts</th>
            <th>Link to Shop</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groupShop->internetShops as $internetShop)
            <tr>
                <td>
                    {{ $groupShop->hide_dealer_name === 0 ? $internetShop->dealer->name : "Dealer " .  $loop->iteration  }}
                </td>
                <td>
                    @if($internetShop->specificDealer)
                    {{ $groupShop->hide_dealer_name === 0 ? $internetShop->specificDealer->name : "Specific Dealer " .  $loop->iteration }}
                    @else
                    {{ $groupShop->hide_dealer_name === 0 ? $internetShop->competitor->name : "Competitor " .  $loop->iteration}}
                    @endif
                </td>
                <td>
                    {{ $internetShop->is_dealer ? 'No' : 'Yes' }}
                </td>
                <td>
                    {{ $internetShop->timezone }}
                </td>
                <td>
                    {{ $internetShop->city->value }}
                </td>
                <td>
                    {{ $internetShop->state->name }}
                </td>
                <td>
                    {{ $internetShop->zip_code }}
                </td>
                <td>
                    {{ $internetShop->shop_type === 'new' ? 'NEW' : 'OLD' }}
                </td>
                <td>
                    {{ $internetShop->source->value }}
                </td>
                <td>
                    {{ $internetShop->source_link }}
                </td>
                <td>
                    {{ $internetShop->vehicle_identification_number }}
                </td>
                <td>
                    {{ $internetShop->make }}
                </td>
                <td>
                    {{ $internetShop->model }}
                </td>
                <td>
                    {{ $internetShop->postedBy->first_name  . ' ' . $internetShop->postedBy->last_name}}
                </td>
                <td>
                    {{ $internetShop->lead_name }}
                </td>
                <td>
                    {{ $internetShop->lead_email }}
                </td>
                <td>
                    {{ $internetShop->lead_phone_number }}
                </td>
                <td>
                    {{ $internetShop->salesperson_name }}
                </td>
                <td>
                    {{ $internetShop->start_at ? \Carbon\Carbon::parse($internetShop->start_at)->format('m/d/Y H:i:s') : '-'}}
                </td>
                <td>
                    {{ $internetShop->shop_duration }}
                </td>
                <td>
                    {{ $internetShop->call_first_received ? \Carbon\Carbon::parse($internetShop->call_first_recevied)->format('m/d/Y H:i:s') : ''}}
                </td>
                <td>
                    {{ $internetShop->call_response_time }}
                </td>
                <td>
                    {{ $internetShop->call_attempts }}
                </td>
                <td>
                    {{ $internetShop->sms_first_recevied ? \Carbon\Carbon::parse($internetShop->sms_first_recevied)->format('m/d/Y H:i:s') : '-' }}
                </td>
                <td>
                    {{ $internetShop->sms_response_time }}
                </td>
                <td>
                    {{ $internetShop->sms_attempts }}
                </td>
                <td>
                    {{$internetShop->email_first_recevied ? \Carbon\Carbon::parse($internetShop->email_first_recevied)->format('m/d/Y H:i:s') : '-'}}
                </td>
                <td>
                    {{ $internetShop->email_response_time }}
                </td>
                <td>
                    {{ $internetShop->email_attempts }}
                </td>
                <td>{{ route('index', [
	'any' => '/guest/internet-shop/' . $internetShop->id
]) }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif



<table>
    <tr></tr>
    <tr></tr>
    <tr></tr>
</table>


@if(count($groupShop->phoneShops))
    <table>
        <thead>
        <tr>
            <th>Name of Company</th>
            <th>Name of Specific Dealer</th>
            <th>City</th>
            <th>State</th>
            <th>Zip Code</th>
            <th>Shop Info</th>
            <th>Shop Date and Time</th>
            <th>Name of SecretShopper</th>
            <th>Lead Name</th>
            <th>Name of Salesperson</th>
            <th>Link to Shop</th>
        </tr>
        </thead>
        <tbody>
        @foreach($groupShop->phoneShops as $phoneShop)
            <tr>
                <td>
                    {{ $groupShop->hide_dealer_name === 0 ? $phoneShop->dealer->name : "Dealer " .  $loop->iteration  }}
                </td>
                <td>
                    {{ $groupShop->hide_dealer_name === 0 ? $phoneShop->specificDealer->name : "Specific Dealer " .  $loop->iteration }}
                </td>
                <td>
                    {{ $phoneShop->city->value }}
                </td>
                <td>
                    {{ $phoneShop->state->name }}
                </td>
                <td>
                    {{ $phoneShop->zip_code }}
                </td>
                <td>
                    {{ $phoneShop->shop_type ? 'New' : 'Used' }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($phoneShop->start_date)->format('m/d/Y H:i:s') }}
                </td>
                <td>
                    {{ $phoneShop->secretShopper->first_name . ' ' . $phoneShop->secretShopper->last_name }}
                </td>
                <td>
                    {{ $phoneShop->lead_name }}
                </td>
                <td>
                    {{ $phoneShop->sales_person_name }}
                </td>
                <td>
                    {{
	                    route('index', [
                        'any' => '/guest/phone-shop/' .  $phoneShop->id
                        ])
                    }}
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endif