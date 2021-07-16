@extends('mailers.shoplayout.base', [
	'image' => $image->value ?? 'https://i.imgur.com/qDxr20f.png',
	'bgColor' => $bgColor ?? '#4c586b'
])
@section('content')
    <!-- REPORT SECTION -->
    <table width="100%" bgcolor="#ededed" align="center" cellpadding="0" cellspacing="0" border="0"
           style="table-layout:fixed;margin:0 auto;mso-table-lspace:0pt;mso-table-rspace:0pt;">
        <tr>
            <td align="center">
                <table width="618" align="center" cellpadding="0" cellspacing="0" border="0" class="table600"
                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                    <tr>
                        <td>
                            <table width="610" align="left" cellpadding="0" cellspacing="0" border="0" class="table600"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                <tr>
                                    <td>
                                        <table width="608" cellpadding="0" cellspacing="0" bgcolor="#ffffff" border="0"
                                               class="table608"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border:1px solid #ffffff; border-radius:0 0 4px 4px;">
                                            <tr>
                                                <td>
                                                    <table align="center" cellpadding="0" cellspacing="0" border="0"
                                                           class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                        <tr>
                                                            <td>
                                                                <table width="608" align="center" cellpadding="0"
                                                                       cellspacing="0" bgcolor="#ffffff" border="0"
                                                                       class="table608"
                                                                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                    <tr>
                                                                        <td width="20" class="wz"></td>
                                                                        <td>
                                                                            <table align="center" cellpadding="0"
                                                                                   cellspacing="0" border="0"
                                                                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                <tr>
                                                                                    <td height="20"
                                                                                        style="font-size:0;line-height:1;">
                                                                                        &nbsp;</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <!-- Secret Shop label and date -->
                                                                                    <td width="564" class="invoiceTD"
                                                                                        style="color: #7f8c9d;font-family: sans-serif;font-size: 19px;text-align: center;line-height: 23px;">
                                                                                        {{ $specificDealer }}<br>{{ $shopDate }}</td>
                                                                                </tr>
                                                                                <tr>
                                                                                    <td height="20"
                                                                                        style="font-size:0;line-height:1;">
                                                                                        &nbsp;</td>
                                                                                </tr>
                                                                            </table>
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
                                                    <hr style="color: #7f8c9d; width: 300px;">
                                                    <table align="center" cellpadding="0" cellspacing="0" border="0"
                                                           class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                        <tr>
                                                            <td>
                                                                <table width="608" align="center" cellpadding="0"
                                                                       cellspacing="0" bgcolor="#ffffff" border="0"
                                                                       class="table608"
                                                                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                    <tr>
                                                                        <td width="20" class="wz"></td>
                                                                        <td>
                                                                            <!--= PHONE SHOP =-->
                                                                            {!!$inbound_call_grade !!}
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
                                                    <table align="center" cellpadding="0" cellspacing="0" border="0"
                                                           class="table608" style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                        <tr>
                                                            <td>
                                                                <table width="608" align="center" cellpadding="0"
                                                                       cellspacing="0" bgcolor="#ffffff" border="0"
                                                                       class="table608"
                                                                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                    <tr>
                                                                        <td align="center">
                                                                            <table align="center" cellpadding="0"
                                                                                   cellspacing="0" bgcolor="#67bffd" border="0"
                                                                                   style="border-radius:4px 4px 4px 4px;mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                <tr>
                                                                                    <td width="20" class="wz"></td>
                                                                                    <td>
                                                                                        <table width="528" align="center"
                                                                                               cellpadding="0" cellspacing="0"
                                                                                               border="0"
                                                                                               class="table528Button"
                                                                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;">
                                                                                            <tr>
                                                                                                <td height="8"
                                                                                                    style="font-size:0;line-height:1;">
                                                                                                    &nbsp;</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <!--BUTTON-->
                                                                                                <!--Use shorter phrases , or the text jumps into an another line-->
                                                                                                <td width="528"
                                                                                                    align="center"
                                                                                                    class="td528Button"
                                                                                                    style="color: #ffffff;font-family: sans-serif;font-size: 15px;font-weight: lighter;text-align: center;line-height: 23px;">
                                                                                                    <a href="{{ $link }}"
                                                                                                       target="_blank"
                                                                                                       style="text-decoration: none;color: #ffffff;display: block;">Listen to Calls</a>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td height="8"
                                                                                                    style="font-size:0;line-height:1;">
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
                                                            <td height="21" class="buttonVrt"
                                                                style="font-size:0;line-height:1;">&nbsp;</td>
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
    <table width="100%" align="center" cellpadding="0" cellspacing="0" border="0" bgcolor="#ededed"
           style="table-layout:fixed;mso-table-lspace:0pt;mso-table-rspace:0pt;">
        <tr>
            <td align="center" width="610" height="30" class="mvd" bgcolor="#ededed" style="font-size:0;line-height:1;">
                &nbsp;</td>
        </tr>
    </table>
    <!--END OF REPORT SECTION-->
@endsection
