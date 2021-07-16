@extends('mailers.generallayout.base')
@section('content')
<!-- START LAYOUT-7 -->
<tr>
    <td align="center" valign="top" style="background-color: #f1f1f1;" bgcolor="#f1f1f1">
        <!-- start container width 600px -->
        <table width="600" align="center" border="0" cellspacing="0" cellpadding="0" class="container"
            style="background-color: #ffffff; margin: 0px auto; width: 600px; min-width: 320px; max-width: 90%;"
            role="presentation" bgcolor="#ffffff">
            <tr>
                <td style="padding: 0px 20px;">
                    <table width="100%" border="0" cellpadding="0" cellspacing="0"
                        style="margin: 0px auto; min-width: 100%;" align="center" role="presentation">
                        <!--start space height -->
                        <tr>
                            <td height="40" style="height: 40px; font-size: 0px; line-height: 0;" aria-hidden="true">
                                &nbsp;</td>
                        </tr>
                        <!--end space height -->
                        <tr>
                            <td valign="top">
                                <!-- start container width 560px -->
                                <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0"
                                    style="table-layout: fixed; margin: 0px auto; min-width: 100%;" role="presentation">
                                    <tr>
                                        <td valign="top">
                                            <table align="center" border="0" cellspacing="0" cellpadding="0"
                                                style="margin: 0px auto;mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                role="presentation">
                                                <tr>
                                                    <td valign="top">
                                                        <table align="center" border="0" cellspacing="0" cellpadding="0"
                                                            style="margin: 0px auto;mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                            role="presentation">
                                                            <!--start space height -->
                                                            <tr>
                                                                <td height="5"
                                                                    style="height: 5px; font-size: 0px; line-height: 0;"
                                                                    aria-hidden="true">&nbsp;</td>
                                                            </tr>
                                                            <!--end space height -->
                                                            <tr>
                                                                <td style="font-size: 25px; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif; color: #555555; font-weight: bold; word-break: break-word; line-height: 1;"
                                                                    align="center"><span
                                                                        style="font-style: normal; text-align: center; color: #555555; line-height: 31px; font-size: 25px; font-weight: 700; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;"><b
                                                                            style="font-size: 25px; font-weight: 700; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;">WELCOME
                                                                            TO YOUR TRAINING SITE!</b></span></td>
                                                            </tr>
                                                            <!--start space height -->
                                                            <tr>
                                                                <td height="5"
                                                                    style="height: 5px; font-size: 0px; line-height: 0;"
                                                                    aria-hidden="true">&nbsp;</td>
                                                            </tr>
                                                            <!--end space height -->
                                                        </table>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                    <!--start space height -->
                                    <tr>
                                        <td height="30" style="height: 30px; font-size: 0px; line-height: 0;"
                                            aria-hidden="true">&nbsp;</td>
                                    </tr>
                                    <!--end space height -->
                                    <tr>
                                        <td valign="top">
                                            <table border="0" cellspacing="0" cellpadding="0" align="center"
                                                style="margin: 0px auto;mso-table-lspace:0pt; mso-table-rspace:0pt;"
                                                role="presentation">
                                                <!-- start image  and content-->
                                            </table>
                                        </td>
                                    </tr>
                                    <!--start space height -->
                                    <tr>
                                        <td height="1" style="height: 1px; font-size: 0px; line-height: 0;"
                                            aria-hidden="true">&nbsp;</td>
                                    </tr>
                                    <!--end space height -->
                                    <tr>
                                        <td valign="top">
                                            <table border="0" cellspacing="0" cellpadding="0" align="left"
                                                role="presentation" style="mso-table-lspace:0pt; mso-table-rspace:0pt;">
                                                <tr>
                                                    <td style="padding-top: 5px; font-size: 14px; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif; color: #333333; font-weight: 300; word-break: break-word; line-height: 1;"
                                                        align="left"><span
                                                            style="color: #333333; font-style: normal; text-align: left; line-height: 24px; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;"><span
                                                                style="font-style: normal; text-align: left; color: #333333; line-height: 24px; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;">Hi
                                                                {{ $user->name }},<br><br>We're so happy that you signed
                                                                up with us.<br><br>Your account has been approved and
                                                                now ready to be used. Let's start with the basics, below
                                                                is your login detail:<br><br>Link:
                                                                @if(strpos($url, 'thehudsonacademy.com') !== false)
                                                                    <a
                                                                        href="https://thehudsonacademy.com/"
                                                                        data-mce-href="https://thehudsonacademy.com/"
                                                                        style="border-style: none; text-decoration: none !important; line-height: 24px; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;"
                                                                        border="0">thehudsonacademy.com
                                                                    </a>
                                                                @else
                                                                    <a
                                                                        href="https://app.webinarinc.com"
                                                                        data-mce-href="https://app.webinarinc.com"
                                                                        style="border-style: none; text-decoration: none !important; line-height: 24px; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;"
                                                                        border="0">app.webinarinc.com
                                                                    </a>
                                                                @endif
                                                                <br>
                                                                Username:
                                                                {{ $user->email }}
                                                                <br>Default Password: Webinarinc123<br><br>
                                                                In case the default password is different from the one that has been registered during the creation of your account, feel free to reset you password from the &nbsp;
                                                                @if(strpos($url, 'thehudsonacademy.com') !== false)
                                                                    <a
                                                                        href="https://thehudsonacademy.com/"
                                                                        data-mce-href="https://thehudsonacademy.com/"
                                                                        style="border-style: none; text-decoration: none !important; line-height: 24px; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;"
                                                                        border="0">login page.
                                                                    </a>
                                                                @else
                                                                    <a
                                                                        href="https://app.webinarinc.com"
                                                                        data-mce-href="https://app.webinarinc.com"
                                                                        style="border-style: none; text-decoration: none !important; line-height: 24px; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;"
                                                                        border="0">login page.
                                                                    </a>
                                                                @endif
                                                                <br><br>
                                                                If ever you have any questions
                                                                regarding the training system, feel free to email us at
                                                                <a href="mailto:admin@webinarinc.com"
                                                                    data-mce-href="mailto:admin@webinarinc.com"
                                                                    style="border-style: none; text-decoration: none !important; line-height: 24px; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;"
                                                                    border="0">admin@webinarinc.com</a>.<br><br>Thanks!<br>Gilbert
                                                                Jacob<br><br><br></span></span></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr><!-- end image and content -->
                                </table><!-- end container width 560px -->
                            </td>
                        </tr>
                        <!--start space height -->
                        <tr>
                            <td height="40" style="height: 40px; font-size: 0px; line-height: 0;" aria-hidden="true">
                                &nbsp;</td>
                        </tr>
                        <!--end space height -->
                    </table>
                </td>
            </tr>
        </table><!-- end container width 600px -->
    </td>
</tr><!-- END LAYOUT-7  -->
@endsection
