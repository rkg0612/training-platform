@extends('mailers.generallayout.base')
@section('content')
<!-- START LAYOUT-7 -->
<tr>
    <td align="center" valign="top" style="background-color: #f1f1f1;" bgcolor="#f1f1f1">
      <!-- start container width 600px -->
      <table width="600" align="center" border="0" cellspacing="0" cellpadding="0" class="container" style="background-color: #ffffff; margin: 0px auto; width: 600px; min-width: 320px; max-width: 90%;" role="presentation" bgcolor="#ffffff">
        <tr>
          <td style="padding: 0px 20px;">
            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin: 0px auto; min-width: 100%;" align="center" role="presentation">
              <!--start space height -->
              <tr>
                <td height="40" style="height: 40px; font-size: 0px; line-height: 0;" aria-hidden="true">&nbsp;</td>
              </tr>
              <!--end space height -->
              <tr>
                <td valign="top">
                  <!-- start container width 560px -->
                  <table width="100%" align="center" border="0" cellspacing="0" cellpadding="0" style="table-layout: fixed; margin: 0px auto; min-width: 100%;" role="presentation">
                    <tr>
                      <td valign="top">
                        <table align="center" border="0" cellspacing="0" cellpadding="0" style="margin: 0px auto;mso-table-lspace:0pt; mso-table-rspace:0pt;" role="presentation"></table>
                      </td>
                    </tr>
                    <!--start space height -->
                    <tr>
                      <td height="1" style="height: 1px; font-size: 0px; line-height: 0;" aria-hidden="true">&nbsp;</td>
                    </tr>
                    <!--end space height -->
                    <tr>
                      <td valign="top">
                        <table border="0" cellspacing="0" cellpadding="0" align="center" style="margin: 0px auto;mso-table-lspace:0pt; mso-table-rspace:0pt;" role="presentation">
                          <!-- start image  and content-->
                        </table>
                      </td>
                    </tr>
                    <!--start space height -->
                    <tr>
                      <td height="1" style="height: 1px; font-size: 0px; line-height: 0;" aria-hidden="true">&nbsp;</td>
                    </tr>
                    <!--end space height -->
                    <tr>
                      <td valign="top">
                        <table border="0" cellspacing="0" cellpadding="0" align="left" role="presentation" style="mso-table-lspace:0pt; mso-table-rspace:0pt;">
                          <tr>
                            <td style="padding-top: 5px; font-size: 14px; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif; color: #333333; font-weight: 300; word-break: break-word; line-height: 1;" align="left"><span style="color: #333333; font-style: normal; text-align: left; line-height: 24px; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;"><span style="font-style: normal; text-align: left; color: #333333; line-height: 24px; font-size: 14px; font-weight: 300; font-family: Roboto, 'Open Sans', Arial, Tahoma, Helvetica, sans-serif;">Hi Admin,<br><br>{{ $user->name }} has signed up for an account with us with {{ $user->email }} as a username.&nbsp;<br>
                                  @if(isset($specificDealer))
                                    @if(is_string($specificDealer))
                                      This user is part of non existing specific dealer we have named {{ $specificDealer }}
                                    @else
                                      This user is part of existing specific dealer we have in our record: {{ $specificDealer->name }} @if($manager != null) under the manager named: {{ $manager->name }}  @endif
                                    @endif
                                    <br>
                                  @endif
                                  <br>Please go to user management section to approve and complete the setup of the new user.<br><br>Thanks!<br>Gilbert Jacob<br><br><br></span></span></td>
                          </tr>
                        </table>
                      </td>
                    </tr><!-- end image and content -->
                  </table><!-- end container width 560px -->
                </td>
              </tr>
              <!--start space height -->
              <tr>
                <td height="40" style="height: 40px; font-size: 0px; line-height: 0;" aria-hidden="true">&nbsp;</td>
              </tr>
              <!--end space height -->
            </table>
          </td>
        </tr>
      </table><!-- end container width 600px -->
    </td>
  </tr>
<!-- END LAYOUT-7  -->
@endsection
