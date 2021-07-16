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
        <th>Name of Secret Shopper</th>
        <th>Lead's Name</th>
        <th>Lead's Email Address</th>
        <th>Lead's Phone Number</th>
        <th>Salesperson's Name</th>
        <th>Start Date and Time (mm/dd/yyyy hh:mm:ss)</th>
        <th>Shop Duration</th>
        <th>First Call Received (mm/dd/yyyy hh:mm:ss)</th>
        <th>Call Response Time</th>
        <th>Call Attempts</th>
        <th>First SMS Received  (mm/dd/yyyy hh:mm:ss)</th>
        <th>SMS Response Time</th>
        <th>SMS Attempts</th>
        <th>First Email Received (mm/dd/yyyy hh:mm:ss)</th>
        <th>First Email Response Time</th>
        <th>Second Email Response Time</th>
        <th>Email Attempts</th>
        <th>Link to Shop</th>
    </tr>
    </thead>
    <tbody>
    @foreach($internetShops as $internetShop)
        <tr>
            <td>{{ $internetShop['dealer']['name'] ?? '' }}</td>
            <td>{{ $internetShop['specific_dealer']['name'] ?? '' }}</td>
            <td>{{ $internetShop['is_dealer'] == 0 ? 'Yes' : 'No' }}</td>
            <td>{{ $internetShop['timezone'] ?? '' }}</td>
            <td>{{ $internetShop['city'] ? $internetShop['city']['value'] : '' }}</td>
            <td>{{ $internetShop['state'] ? $internetShop['state']['abbreviation'] : '' }}</td>
            <td>{{ $internetShop['zip_code'] ?? '' }}</td>
            <td>{{ $internetShop['is_shop_new'] == 1 ? 'New' : 'Used' }}</td>
            <td>{{ $internetShop['source'] ? $internetShop['source']['value'] : '' }}</td>
            <td>{{ $internetShop['source_link'] ?? '' }}</td>
            <td>{{ $internetShop['vehicle_identifcation_number'] ?? '' }}</td>
            <td>{{ $internetShop['make'] ?? '' }}</td>
            <td>{{ $internetShop['model'] ?? '' }}</td>
            <td>{{ $internetShop['posted_by'] ? $internetShop['posted_by']['name'] : '' }}</td>
            <td>{{ $internetShop['lead_name'] ?? '' }}</td>
            <td>{{ $internetShop['lead_email'] ?? '' }}</td>
            <td>{{ $internetShop['lead_phone_number'] ?? '' }}</td>
            <td>{{ $internetShop['salesperson_name'] ?? '' }}</td>
            <td>{{ $carbon->format('m/d/Y H:i:s', $internetShop['start_at']) ?? '' }}</td>
            <td>{{ $internetShop['shop_duration'] ?? '' }}</td>
            <td>{{ $carbon->format('m/d/Y H:i:s', $internetShop['call_first_received']) ?? '' }}</td>
            <td>{{ $internetShop['call_response_time'] ?? '' }}</td>
            <td>{{ $internetShop['call_attempts'] ?? '' }}</td>
            <td>{{ $carbon->format('m/d/Y H:i:s', $internetShop['sms_first_received']) ?? '' }}</td>
            <td>{{ $internetShop['sms_response_time'] ?? '' }}</td>
            <td>{{ $internetShop['sms_attempts'] ?? '' }}</td>
            <td>{{ $carbon->format('m/d/Y H:i:s', $internetShop['email_first_received']) ?? '' }}</td>
            <td>{{ $internetShop['email_response_time'] ?? '' }}</td>
            <td>{{ $internetShop['email_second_response_time'] ?? '' }}</td>
            <td>{{ $internetShop['email_attempts'] ?? '' }}</td>
            <td>{{ env('APP_URL') . 'client/internet-shop/' . $internetShop['id'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
