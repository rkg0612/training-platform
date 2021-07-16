@extends('mailers.shoplayout.base')
@section('content')
<!-- REPORT SECTION -->
<table width="100%" bgcolor="#ededed" align="center" cellpadding="0" cellspacing="0" border="0" style="table-layout:fixed;margin:0 auto;mso-table-lspace:0pt;mso-table-rspace:0pt;">
    <tr>
        <td align="center">
            <table width="618" align="center" cellpadding="0" cellspacing="0" border="0" class="table600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                <tr>
                    <td>
                        <table width="610" align="left" cellpadding="0" cellspacing="0" border="0" class="table600" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                            <tr>
                                <td>
                                    <table width="608" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;border:1px solid #ffffff; border-radius:0 0 4px 4px;">
                                        <tr>
                                            <td>
                                                <table align="center" cellpadding="0" cellspacing="0" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                    <tr>
                                                        <td>
                                                            <table width="608" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                <tr>
                                                                    <td width="20" class="wz"></td>
                                                                    <td>
                                                                        <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                            <tr>
                                                                                <td height="20" style="font-size:0;line-height:1;">
                                                                                    &nbsp;</td>
                                                                            </tr>
                                                                            <tr>
                                                                                <!-- Secret Shop label and date -->
                                                                                <td width="564" class="invoiceTD" style="color: #7f8c9d;font-family: sans-serif;font-size: 19px;text-align: center;line-height: 23px;">
                                                                                    {{ is_null($groupShop->specificDealer) ? $groupShop->dealer->name : $groupShop->specificDealer->name }}
                                                                                    Secret Shop
                                                                                    Report<br>
                                                                                    {{ $groupShop->created_at->setTimezone('America/New_York')->format('m/d/Y') }}
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td height="20" style="font-size:0;line-height:1;">
                                                                                    &nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                        <!--= START RESPONSE TIME GROUP =-->
                                                                        <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                            <tr>
                                                                                <td colspan="5" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                                                                    INTERNET SHOP - RESPONSE
                                                                                    TIME</td>
                                                                            </tr>
                                                                            <tr style="background-color:#f9f9f9;">
                                                                                <td width="190" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; font-weight: 800; padding-left: 10px;">
                                                                                    Dealer's Name</td>
                                                                                <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    Email</td>
                                                                                <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    Call</td>
                                                                                <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    SMS</td>
                                                                            </tr>
                                                                            @foreach($internetShop['response'] as $dealer => $shop)
                                                                                <tr style="background-color:{{ $loop->even ? '#f9f9f9' : '#f4f4f4' }};">
                                                                                    <td width="190" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                                                                        {{ $dealer }}
                                                                                    </td>
                                                                                    <td width="90" valign="middle" height="25" class="invReg text-{{ $shop['email']['color'] }}" style="font-family: sans-serif;font-size: 13px;text-align: center;color:{{ $shop['email']['color'] }} !important">
                                                                                        {{ $shop['email']['value'] }}
                                                                                    </td>
                                                                                    <td width="90" valign="middle" height="25" class="invReg text-{{ $shop['call']['color'] }}" style="font-family: sans-serif;font-size: 13px;text-align: center;color: {{$shop['call']['color']}} !important">
                                                                                        {{ $shop['call']['value'] }}
                                                                                    </td>
                                                                                    <td width="90" valign="middle" height="25" class="invReg text-{{ $shop['sms']['color'] }}" style="font-family: sans-serif;font-size: 13px;text-align: center; color: {{ $shop['sms']['color'] }} !important">
                                                                                        {{ $shop['sms']['value'] }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                            <tr>
                                                                                <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                                                                    &nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                        <!--= Start of Legend for Response Time =-->
                                                                        <div style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 11px;">
                                                                            <p>
                                                                                Email is green if < {{ $dealerOptions['email_yellow_response_time'] }},
                                                                                yellow if > {{ $dealerOptions['email_yellow_response_time'] }} but < {{ $dealerOptions['email_red_response_time'] }}
                                                                                and red if > {{ $dealerOptions['email_red_response_time'] }}.
                                                                            </p>
                                                                            <p>
                                                                                Call is green if < {{ $dealerOptions['call_yellow_response_time'] }},
                                                                                yellow if > {{ $dealerOptions['call_yellow_response_time'] }} but < {{ $dealerOptions['call_red_response_time'] }}
                                                                                and red if > {{ $dealerOptions['call_red_response_time'] }}.
                                                                            </p>
                                                                            <p>
                                                                                SMS is green if < {{ $dealerOptions['sms_yellow_response_time'] }},
                                                                                yellow if > {{ $dealerOptions['sms_yellow_response_time'] }} but < {{ $dealerOptions['sms_red_response_time'] }}
                                                                                and red if > {{ $dealerOptions['sms_red_response_time'] }}.
                                                                            </p>
                                                                        </div>
                                                                        <!--= End of Legend for Response Time =-->
                                                                        <!--= END RESPONSE TIME GROUP =-->
                                                                        <!--= START SHOP ATTEMPTS=-->
                                                                        <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                            <tr>
                                                                                <td colspan="5" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                                                                    INTERNET SHOP - ATTEMPTS
                                                                                </td>
                                                                            </tr>
                                                                            <tr style="background-color:#f9f9f9;">
                                                                                <td width="190" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; font-weight: 800; padding-left: 10px;">
                                                                                    Dealer's Name</td>
                                                                                <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    Email</td>
                                                                                <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    Call</td>
                                                                                <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    SMS</td>
                                                                            </tr>
                                                                            @foreach($internetShop['attempt'] as $dealer => $shop)
                                                                                <tr style="background-color:{{ $loop->even ? '#f9f9f9' : '#f4f4f4' }};">
                                                                                    <td width="190" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                                                                        {{ $dealer }}
                                                                                    </td>
                                                                                    <td width="90" valign="middle" height="25" class="invReg text-{{ $shop['email']['color'] }}" style="font-family: sans-serif;font-size: 13px;text-align: center; color: {{ $shop['email']['color'] }} !important;">
                                                                                        {{ $shop['email']['value'] }}
                                                                                    </td>
                                                                                    <td width="90" valign="middle" height="25" class="invReg text-{{ $shop['call']['color'] }}" style="font-family: sans-serif;font-size: 13px;text-align: center; color: {{ $shop['call']['color'] }} !important;">
                                                                                        {{ $shop['call']['value'] }}
                                                                                    </td>
                                                                                    <td width="90" valign="middle" height="25" class="invReg text-{{ $shop['sms']['color'] }}" style="font-family: sans-serif;font-size: 13px;text-align: center; color:  {{ $shop['sms']['color'] }} !important;">
                                                                                        {{ $shop['sms']['value'] }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                            <tr>
                                                                                <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                                                                    &nbsp;</td>
                                                                            </tr>
                                                                        </table>
                                                                        <!--= Start of Legend for Attempts =-->
                                                                        <div style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 11px;">
                                                                            <p>
                                                                                Email is green if {{ $dealerOptions['email_green_attempts'] }}+ attempts w/in 5 days,
                                                                                yellow if {{ $dealerOptions['email_yellow_attempts'] - 1 }} - {{ $dealerOptions['email_yellow_attempts'] }},
                                                                                and red if {{ $dealerOptions['email_red_attempts'] - 1 }} - {{ $dealerOptions['email_red_attempts'] }}.
                                                                            </p>
                                                                            <p>
                                                                                Call is green if {{ $dealerOptions['call_green_attempts'] }}+ attempts w/in 5 days,
                                                                                yellow if {{ $dealerOptions['call_yellow_attempts'] - 1 }} - {{ $dealerOptions['call_yellow_attempts'] }},
                                                                                and red if {{ $dealerOptions['call_red_attempts'] - 1 }} - {{ $dealerOptions['call_red_attempts'] }}.
                                                                            </p>
                                                                            <p>
                                                                                SMS is green if {{ $dealerOptions['sms_green_attempts'] }}+ attempts w/in 5 days,
                                                                                yellow if {{ $dealerOptions['sms_yellow_attempts'] - 1 }} - {{ $dealerOptions['sms_yellow_attempts'] }},
                                                                                and red if {{ $dealerOptions['sms_red_attempts'] - 1 }} - {{ $dealerOptions['sms_red_attempts'] }}.
                                                                            </p>
                                                                        </div>
                                                                        <!--= End of Legend for Attempts =-->
                                                                        <!--= END SHOP ATTEMPTS =-->
                                                                        <!--= START OF ADDITIONAL DETAILS =-->
                                                                        <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                            <tr>
                                                                                <td colspan="5" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                                                                    ADDITIONAL DETAILS</td>
                                                                            </tr>
                                                                            <tr style="background-color:#f9f9f9;">
                                                                                <td width="187" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    Make and Model</td>
                                                                                <td width="187" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    Lead Name</td>
                                                                                <td width="187" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    Name of Salesperson</td>
                                                                                <td width="187" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                                                    Secret Shop Date and Time
                                                                                </td>
                                                                            </tr>
                                                                            @foreach($internetShop['additionalDetails'] as $dealer => $value)
                                                                                <tr style="background-color:{{ $loop->even ? '#f9f9f9' : '#f4f4f4' }};">
                                                                                    <td width="190" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                                                                        {{ $value['makeAndModel'] }}
                                                                                    </td>
                                                                                    <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                                                                        {{ $value['leadName'] }}
                                                                                    </td>
                                                                                    <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                                                                        {{ $value['salesperson'] }}
                                                                                    </td>
                                                                                    <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                                                                        {{ $value['date'] }}
                                                                                    </td>
                                                                                </tr>
                                                                            @endforeach
                                                                            <tr>
                                                                                <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                                                                    &nbsp;</td>
                                                                            </tr>
                                                                        </table>

                                                                        <!--= END OF ADDITIONAL DETAILS =-->
                                                                    </td>
                                                                    <td width="20" class="wz"></td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="15" style="font-size:0;line-height:1;">
                                                            &nbsp;</td>
                                                    </tr>
                                                </table>
                                                <table align="center" cellpadding="0" cellspacing="0" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                    <tr>
                                                        <td>
                                                            <table width="608" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                <tr>
                                                                    <td align="center">
                                                                        <table align="center" cellpadding="0" cellspacing="0" bgcolor="#67bffd" border="0" style="border-radius:4px 4px 4px 4px;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                            <tr>
                                                                                <td width="20" class="wz"></td>
                                                                                <td>
                                                                                    <table width="528" align="center" cellpadding="0" cellspacing="0" border="0" class="table528Button" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                        <tr>
                                                                                            <td height="8" style="font-size:0;line-height:1;">
                                                                                                &nbsp;</td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <!--BUTTON-->
                                                                                            <!--Use shorter phrases , or the text jumps into an another line-->
                                                                                            <td width="528" align="center" class="td528Button" style="color: #ffffff;font-family: sans-serif;font-size: 15px;font-weight: lighter;text-align: center;line-height: 23px;">
                                                                                                <a href="{{ env('APP_URL') . "client/group-shops/{$groupShop->id}" }}" target="_blank" style="text-decoration: none;color: #ffffff;display: block;">Click for Detailed Report</a>
                                                                                            </td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td height="8" style="font-size:0;line-height:1;">
                                                                                                &nbsp;</td>
                                                                                        </tr>
                                                                                    </table>
                                                                                </td>
                                                                                <td width="20" class="wz"></td>
                                                                            </tr>
                                                                        </table>
                                                                    </td>
                                                                </tr>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td height="21" class="buttonVrt" style="font-size:0;line-height:1;">&nbsp;</td>
                                                    </tr>
                                                </table>
                                                <hr style="color: #7f8c9d; width: 300px;">
                                                @if(!is_null($phoneShop))
                                                    <table align="center" cellpadding="0" cellspacing="0" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                        <tr>
                                                            <td>
                                                                <table width="608" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                    <tr>
                                                                        <td width="20" class="wz"></td>
                                                                        <td>
                                                                            <!--= PHONE SHOP =-->
                                                                            <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                <tr>
                                                                                    <td colspan="5" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                                                                        PHONE SHOP - GRADE</td>
                                                                                </tr>
                                                                                {!! $phoneShop->inbound_call_grade !!}
                                                                                <tr>
                                                                                    <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                                                                        &nbsp;</td>
                                                                                </tr>
                                                                            </table>
                                                                            <!--= END OF PHONE SHOP =-->
                                                                        </td>
                                                                        <td width="20" class="wz"></td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="15" style="font-size:0;line-height:1;">
                                                                &nbsp;</td>
                                                        </tr>
                                                    </table>
                                                    <table align="center" cellpadding="0" cellspacing="0" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                        <tr>
                                                            <td>
                                                                <table width="608" align="center" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0" class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                    <tr>
                                                                        <td align="center">
                                                                            <table align="center" cellpadding="0" cellspacing="0" bgcolor="#67bffd" border="0" style="border-radius:4px 4px 4px 4px;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                <tr>
                                                                                    <td width="20" class="wz"></td>
                                                                                    <td>
                                                                                        <table width="528" align="center" cellpadding="0" cellspacing="0" border="0" class="table528Button" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                            <tr>
                                                                                                <td height="8" style="font-size:0;line-height:1;">
                                                                                                    &nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <!--BUTTON-->
                                                                                                <!--Use shorter phrases , or the text jumps into an another line-->
                                                                                                <td width="528" align="center" class="td528Button" style="color: #ffffff;font-family: sans-serif;font-size: 15px;font-weight: lighter;text-align: center;line-height: 23px;">
                                                                                                    <a href="{{ env('APP_URL') . "client/phone-shops/{$phoneShop->id}" }}" target="_blank" style="text-decoration: none;color: #ffffff;display: block;">Listen to Calls</a>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td height="8" style="font-size:0;line-height:1;">
                                                                                                    &nbsp;</td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                    <td width="20" class="wz"></td>
                                                                                </tr>
                                                                            </table>
                                                                        </td>
                                                                    </tr>
                                                                </table>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td height="21" class="buttonVrt" style="font-size:0;line-height:1;">&nbsp;</td>
                                                        </tr>
                                                    </table>
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
<table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ededed" style="table-layout:fixed;mso-table-lspace:0pt;mso-table-rspace:0pt;">
    <tr>
        <td align="center" width="610" height="30" class="mvd" bgcolor="#ededed" style="font-size:0;line-height:1;">
            &nbsp;</td>
    </tr>
</table>
<!--END OF REPORT SECTION-->
@endsection
