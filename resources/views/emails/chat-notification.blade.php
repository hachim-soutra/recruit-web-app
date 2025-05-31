<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<body
    style="border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;background-color: #f3f2f1;font-family: verdana;">
    <table width="100%"
        style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
        <tbody
            style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
            <tr
                style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                <td class="column"
                    style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                    <table width="100%" border="0"
                        style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                        <tbody align="align="center""
                            style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                            <tr align="center"
                                style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                <td align="center"
                                    style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;padding: 48px 50px 16px;">

                                    <img src="{{ asset('backend/img/job-portal-logo.png') }}" alt="logo"
                                        style="padding: 0;margin-bottom: 5px;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top; text-align:center ">

                                </td>
                            </tr>
                        </tbody>
                    </table>
                </td>
            </tr>
            <tr
                style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                <td width="600" align="center">
                    <table class="section main-content" cellpadding="20" cellspacing="0" width="600"
                        style="padding: 0;margin: 30px 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;background-color: #fff;padding: 20px">

                        <tr
                            style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                            <td class="column"
                                style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;    padding: 24px 50px 16px;">
                                <table width="100%"
                                    style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                    <tbody
                                        style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                        <tr
                                            style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                            <td align="left"
                                                style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                                <table width="100%" cellpadding="0" cellspacing="0">
                                                    <tbody>
                                                        <tr>
                                                            <td style="padding:0 0 4px; line-heigt:1.3" align="left">
                                                                <p>
                                                                    Hello,<br /><br />
                                                                    You have a new message on recruit.ie, login to check
                                                                    now.
                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:0 0 4px" align="left">
                                                                <p>
                                                                    {{ $chat->user_last_message->full_name }}: <a
                                                                        href="{{ $chat->user_last_message->id == $chat->author_id ? route('job-seeker.chat') : route('career-coach.chat') }}">{{ $chat->last_message['message'] }}</a>
                                                                    <br>


                                                                </p>
                                                            </td>
                                                        </tr>
                                                        <tr>
                                                            <td style="padding:0 0 4px" align="left">
                                                                <p>
                                                                    Best Regards,
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td>
                                                <table cellpadding="0" cellspacing="0"
                                                    style="width:100%;border:0;border-spacing:0">
                                                    <tbody>
                                                        <tr
                                                            style="padding: 0;margin: 0px;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                                            <td align="center"
                                                                style="padding-top: 20px;margin: 0;border: none;color: #333;border-spacing: 0;border-collapse: collapse;vertical-align: top;font-weight: 600; text-align: center">
                                                                www.recruit.ie
                                                            </td>
                                                        </tr>

                                                    </tbody>
                                                </table>
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                            </td>
                        </tr>


                        <tr align="center"
                            style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                            <td align="center"
                                style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;text-align: center;">
                                <small class="last-row"
                                    style="background-color: #eaeaea;text-align: center;display: block;width: 100%;color: #666;padding-top: 15px;padding-bottom: 15px;margin-top: 20px;">
                                    {{ $settings->copyright_content }}
                                </small>

                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
