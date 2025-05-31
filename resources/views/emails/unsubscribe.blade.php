<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>

<head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Recruit.ie</title>

</head>

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
                        <tbody align="center"
                            style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                            <tr align="center"
                                style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                <td align="center"
                                    style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;padding: 48px 50px 16px;">
                                    <a href="{{ route('welcome') }}" target="_blank">
                                        <img src="{{ asset('backend/img/job-portal-logo.png') }}" alt="logo"
                                            style="padding: 0;margin-bottom: 5px;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top; text-align:center ">
                                    </a>
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
                                                            <td align="left"
                                                                style="font-size:14px;line-height:20px;font-weight:400;color:#2d2d2d;padding:0 0 4px">
                                                                Hello,
                                                                <br>
                                                                <br>
                                                                This is to inform you that the user with the email
                                                                address {{ $email }} has unsubscribed from our
                                                                newsletter.
                                                                <br>
                                                                <br>

                                                                Kindly ensure their details are removed from our mailing
                                                                list.
                                                                <br>
                                                                <br>
                                                                Thank you.
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
                    </table>
                </td>
            </tr>
            <tr>
                <td lang="en" align="center">
                    <a href="{{ route('welcome') }}" target="_blank">
                        <img src="{{ asset('backend/img/job-portal-logo.png') }}" alt="logo"
                            style="padding: 0;margin-bottom: 5px;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                    </a>
                </td>
            </tr>
            <tr>
                <td lang="en" align="center" style="padding:20px 0px 0px;font-weight: bold;color: #2d2d2d;">
                    © {{ now()->year }} <span class="il"><a href="{{ route('welcome') }}" target="_blank"
                            style=" text-decoration: none;color: #2d2d2d;">Recruit.ie</a></span>
                </td>
            </tr>
            <tr>
                <td lang="en" align="center" style="padding:20px 0px 0px;color: #2d2d2d;">
                    61 Lower Kilmacud Rd, Stillorgan, Dublin, A94 A2F7
                </td>
            </tr>
            <tr>
                <td lang="en" align="center" style="padding:20px 0px 0px;color: #2d2d2d;">
                    Recruit.ie processes and analyses your activity in this email.
                </td>
            </tr>
            <tr>
                <td class="m_-6547982340322844967r-6" align="center"
                    style="padding: 20px 0px 64px;line-height: 36px;color: #2d2d2d;">

                    <a href="{{ route('welcome') }}" title="Recruit.ie"
                        style="color:#f10c37;text-decoration:underline;font-weight: bold;" target="_blank">
                        Recruit.ie</a>
                    <span> | </span>
                    <a href="{{ route('privacy') }}" title="Privacy policy"
                        style="color:#f10c37;text-decoration:underline;font-weight: bold;"
                        class="m_-6547982340322844967r-0" target="_blank">Privacy policy</a>
                    <span> | </span>
                    <a href="{{ route('term_of_use') }}" title="Terms"
                        style="color:#f10c37;text-decoration:underline;font-weight: bold;" target="_blank">Terms</a>
                    <span>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
