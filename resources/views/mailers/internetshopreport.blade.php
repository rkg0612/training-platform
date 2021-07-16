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
                                          {{ $shop->specificDealer->name }} Internet Shop
                                          Report<br> {{ $shop->start_at_formatted }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    <!--= START RESPONSE TIME GROUP =-->
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="5" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          INTERNET SHOP - RESPONSE
                                          TIME</td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; font-weight: 800; padding-left: 10px;">
                                          Dealer's Name</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          First Email</td>
                                          @if($shop->dealer_id == 48)
                                              <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                                  Second Email</td>
                                          @endif
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          Call</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          SMS</td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                          {{ $shop->specificDealer->name }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: <?php echo response_time_color_code($shop->email_response_time, 'email', $shop->dealer->options) ?>;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($shop->email_response_time) ? $shop->email_response_time : '--:--' }}
                                        </td>
                                          @if($shop->dealer_id == 48)
                                              <td width="90" valign="middle" height="25" class="invReg" style="color: <?php echo response_time_color_code($shop->email_second_response_time, 'email', $shop->dealer->options) ?>;font-family: sans-serif;font-size: 13px;text-align: center;">
                                                  {{ ($shop->email_second_response_time) ? $shop->email_second_response_time : '--:--' }}
                                              </td>
                                          @endif
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: <?php echo response_time_color_code($shop->call_response_time, 'call', $shop->dealer->options) ?>;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($shop->call_response_time) ? $shop->call_response_time : '--:--' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: <?php echo response_time_color_code($shop->sms_response_time, 'sms', $shop->dealer->options) ?>;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($shop->sms_response_time) ? $shop->sms_response_time : '--:--' }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @if($shop->dealer_id === 48)
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;  width: 560px;">
                                      <tr>
                                        <td colspan="5" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          NATIONAL AVERAGE RESPONSE MTD</td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; font-weight: 800; padding-left: 10px;">
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          Average Email</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          Average Call</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          Average SMS</td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                          Top 10%</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['email']['top_response_time']) ? $nationalAverage['email']['top_response_time'] : '--:--' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['call']['top_response_time']) ? $nationalAverage['call']['top_response_time'] : '--:--' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['sms']['top_response_time']) ? $nationalAverage['sms']['top_response_time'] : '--:--' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                          Bottom 10%</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['email']['bottom_response_time']) ? $nationalAverage['email']['bottom_response_time'] : '--:--' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['call']['bottom_response_time']) ? $nationalAverage['call']['bottom_response_time'] : '--:--' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['sms']['bottom_response_time']) ? $nationalAverage['sms']['bottom_response_time'] : '--:--' }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @endif
                                    <!--= Start of Legend for Response Time =-->
                                    <div style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 11px;">
                                      <p>
                                        Email is green if < {{ $dealerOptions['email_yellow_response_time'] }}, yellow if> {{ $dealerOptions['email_yellow_response_time'] }} but < {{ $dealerOptions['email_red_response_time'] }} and red if> {{ $dealerOptions['email_red_response_time'] }}.
                                      </p>
                                      <p>
                                        Call is green if < {{ $dealerOptions['call_yellow_response_time'] }}, yellow if> {{ $dealerOptions['call_yellow_response_time'] }} but < {{ $dealerOptions['call_red_response_time'] }} and red if> {{ $dealerOptions['call_red_response_time'] }}.
                                      </p>
                                      <p>
                                        SMS is green if < {{ $dealerOptions['sms_yellow_response_time'] }}, yellow if> {{ $dealerOptions['sms_yellow_response_time'] }} but < {{ $dealerOptions['sms_red_response_time'] }} and red if> {{ $dealerOptions['sms_red_response_time'] }}.
                                      </p>
                                    </div>
                                    <!--= End of Legend for Response Time =-->
                                    <!--= END RESPONSE TIME GROUP =-->
                                    <!--= START SHOP ATTEMPTS=-->
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="5" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          INTERNET SHOP - ATTEMPTS
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; font-weight: 800; padding-left: 10px;">
                                          Dealer's Name</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          Email</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          Call</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          SMS</td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                          {{ $shop->specificDealer->name }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: <?php echo attempts_color_code($shop->email_attempts, 'email', $shop->dealer->options) ?>;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($shop->email_attempts && 0 != $shop->email_attempts) ? $shop->email_attempts : 'NA' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: <?php echo attempts_color_code($shop->call_attempts, 'call', $shop->dealer->options) ?>;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($shop->call_attempts && 0 != $shop->call_attempts) ? $shop->call_attempts : 'NA' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: <?php echo attempts_color_code($shop->sms_attempts, 'sms', $shop->dealer->options) ?>;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($shop->sms_attempts && 0 != $shop->sms_attempts) ? $shop->sms_attempts : 'NA' }}
                                        </td>
                                      </tr>

                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @if($shop->dealer_id === 48)
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt;width: 560px;">
                                      <tr>
                                        <td colspan="5" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          NATIONAL AVERAGE ATTEMPTS MTD
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; font-weight: 800; padding-left: 10px;">
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          Average Email</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          Average Call</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center; font-weight: 800;">
                                          Average SMS</td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                          Top 10%</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['email']['top_attempts'] && 0 != $nationalAverage['email']['top_attempts']) ? $nationalAverage['email']['top_attempts'] : 'NA' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['call']['top_attempts'] && 0 != $nationalAverage['call']['top_attempts']) ? $nationalAverage['call']['top_attempts'] : 'NA' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['sms']['top_attempts'] && 0 != $nationalAverage['sms']['top_attempts']) ? $nationalAverage['sms']['top_attempts'] : 'NA' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="200" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                          Bottom 10%</td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['email']['bottom_attempts'] && 0 != $nationalAverage['email']['bottom_attempts']) ? $nationalAverage['email']['bottom_attempts'] : 'NA' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['call']['bottom_attempts'] && 0 != $nationalAverage['call']['bottom_attempts']) ? $nationalAverage['call']['bottom_attempts'] : 'NA' }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ ($nationalAverage['sms']['bottom_attempts'] && 0 != $nationalAverage['sms']['bottom_attempts']) ? $nationalAverage['email']['bottom_attempts'] : 'NA' }}
                                        </td>
                                      </tr>

                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @endif
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
                                          Date and Time Shopped
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->make }} - {{ $shop->model }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->lead_name }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->salesperson_name }}
                                        </td>
                                        <td width="90" valign="middle" height="25" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->start_at_formatted }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>

                                    <!--= END OF ADDITIONAL DETAILS =-->
                                    <br>
                                    @if($shop->dealer_id === 48)
                                    <!--= START OF RECOMMENDATION DETAILS =-->
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="2" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          RECOMMENDATION</td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Sent Picture / Video</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_send_picvid ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Mentioned TrueCar in Voicemail</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_mention_ts_voicemail ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Mentioned TrueCar in Email</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_mention_ts_email ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Mentioned TrueCar on the Text Message</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_mention_ts_text ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Mentioned TrueCar on the Video</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_mention_ts_video ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Was there an appointment attempt?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_attempt_appointment ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Was there home delivery offer</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_home_delivery_offer ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Email</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->recommendation_email}}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Call</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->recommendation_call}}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          SMS</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->recommendation_sms}}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>

                                    <!--= END OF RECOMMENDATION DETAILS =-->
                                    {{-- START SPECIALTY PACKAGE DETAILS --}}
                                    @if($shop->truecar_fields->shop_type === 'text_only')
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="2" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          Text Only</td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Were the texts 135 characters/22 words or less?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->to_meet_char_limits ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @endif
                                    @if($shop->truecar_fields->shop_type === 'access_base')
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="2" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          Access Base</td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did the dealer provide payments based on adjusting the total down?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->ab_provide_payment ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did the dealer honor the TrueCash Offer from TCDC?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->ab_honor_offer_tcdc ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Mentioned TrueCar in Email</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_mention_ts_email ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Is the manual discount of at least $100 being offered in the price quote?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->ab_manual_discount_offer ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 1) What will my payment be if I put $3500 total down?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->ab_script_what_payment }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 2) When I was on TrueCar, it displayed a payment of $XX with due at signing $XXXX on this car. Is this a real payment?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->ab_script_real_payment }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @endif
                                    @if($shop->truecar_fields->shop_type === 'military')
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="2" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          Military</td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did the dealer identify the customer by rank?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: center;">
                                          {{ $shop->truecar_fields->military_identify_rank ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did the dealer thank customer for their service?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->military_thank_customer ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Were the military appreciation benefits explained?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->military_benefits_explained ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script) Can you please explain the military benefits to me?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->military_script_benefits }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @endif
                                    @if($shop->truecar_fields->shop_type === 'penfed')
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="2" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          PenFed</td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Do they honor the “no flipping” policy? (Script 1)</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->pf_honor_nf_policy ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Were there any contingencies for bringing financing from PenFed? (If yes, input verbatim below)</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->pf_contingencies ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did they mention Buyer’s Bonus?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->pf_buyers_bonus ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Mentioned TrueCar on the Text Message</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_mention_ts_text ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Is the manual discount of at least $100 being offered in the price quote?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->pf_manual_discount_offer ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 1) I’ve been pre-approved for a loan through PenFed. Will that be accepted for my vehicle financing?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->pf_script_approved_loan }}
                                        </td>
                                      </tr>
                                      @if($shop->truecar_fields->pf_contingencies)
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Were there any contingencies for bringing financing from PenFed? (If yes, input verbatim)</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->pf_contingencies_if_yes }}
                                        </td>
                                      </tr>
                                      @endif
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @endif
                                    @if($shop->truecar_fields->shop_type === 'amex')
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="2" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          AMEX</td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Allowed to put money down on AMEX card? (Script 1)</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->amex_allowed_amex ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did the dealer mention Buyer’s Bonus?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->amex_mention_bonus ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Is the manual discount of at least $100 being offered in the price quote?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->amex_manual_discount_offer ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Mentioned TrueCar on the Text Message</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->bs_mention_ts_text ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 1) I see on the American Express Auto Purchasing program site that I can use my card for some of the car purchase. Can I use my AMEX Card to put money down at your dealership?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->amex_script_use_amex }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: left; padding-left: 10px;">
                                          Was there an appointment attempt?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;">
                                          {{ $shop->truecar_fields->bs_attempt_appointment ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 2) Do you have a limit on how much I can put on the card?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->amex_script_limit }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @endif
                                    @if($shop->truecar_fields->shop_type === 'sams_club')
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="2" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          Sam's Club</td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did they mention unprompted Buyer’s Bonus or any potential post-sale benefits, e.g. Pandora gift card?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->sc_unprompted_bonus ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did dealer mention they were a Sam’s Club member themselves to bridge relationship gap on introductory email/call?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->sc_bridge_rel_gap ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did they mention any other partners they work with TrueCar on during the conversation?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->sc_partners ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Is the manual discount of at least $100 being offered in the price quote?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->sc_manual_discount_offer ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did dealer say at any point if they didn’t know the answer to ask the question at their local Sam’s Club store?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->sc_didnt_know_answer ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 1) I have a Sam’s Club and Costco membership, can I get a better deal going through Costco and you?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->sc_script_costco }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 2) I saw on the Sam’s Club website that you are doing something with Pandora. Can you tell me more about that?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->sc_script_pandora }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 3) What’s my payment if I put $3500 total down?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->sc_script_what_payment }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @endif
                                    @if($shop->truecar_fields->shop_type === 'consumer_reports')
                                    <table align="center" cellpadding="0" cellspacing="0" border="0" style="mso-table-lspace:0pt;mso-table-rspace:0pt; width: 560px;">
                                      <tr>
                                        <td colspan="2" valign="middle" height="40" class="invCap" style="color: #7f8c9d;font-family: sans-serif;text-align: center;font-size: 15px;">
                                          Consumer Reports</td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did the dealer mention any comments about Consumer Reports and their reviews/recommendations of vehicles?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: center;">
                                          {{ $shop->truecar_fields->cr_comments ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did the dealer mention they were a Consumer Reports member themselves to bridge relationship gap on introductory email/call?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: center;">
                                          {{ $shop->truecar_fields->cr_bridge_rel_gap ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Was there an appointment schedule attempt?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->cr_appointment_attempt ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Was there a home delivery offer?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->cr_home_delivery ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did they mention any other partners they work with TrueCar on during the conversation?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->cr_partners ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Is the manual discount of at least $100 being offered in the price quote?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->cr_manual_discount_offer ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          Did the dealer say at any point if they didn’t know the answer to ask the question through Consumer Reports’ website?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px;text-align: center;">
                                          {{ $shop->truecar_fields->cr_didnt_know_answer ? 'Yes' : 'No' }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f4f4f4;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 1) When I buy my car from you, will it appear within the “My Products” area on the website?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->cr_script_my_products_web }}
                                        </td>
                                      </tr>
                                      <tr style="background-color:#f9f9f9;">
                                        <td width="187" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          (Script 2) What’s my payment if I put $3500 total down?</td>
                                        <td width="90" valign="middle" height="50" class="invReg" style="color: #7f8c9d;font-family: sans-serif;font-size: 13px; text-align: left; padding-left: 10px;">
                                          {{ $shop->truecar_fields->cr_script_what_payment }}
                                        </td>
                                      </tr>
                                      <tr>
                                        <td colspan="3" height="20" style="font-size:0;line-height:1;">
                                          &nbsp;</td>
                                      </tr>
                                    </table>
                                    @endif
                                    {{-- END SPECIALTY PACKAGE DETAILS --}}
                                    @endif
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
                                                <a href="{{ $detailedReportLink }}" target="_blank" style="text-decoration: none;color: #ffffff;display: block;">Click for Detailed Report</a>
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
