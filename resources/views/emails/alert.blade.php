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
                                        <img src="{{ asset('backend/img/job-portal-logo.png') }}" alt=""
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

                        <tr>
                            <td style="padding:36px 0 24px">
                                <h1 align="center"
                                    style="color:#2d2d2d;font-size:24px;font-weight:bold;line-height:36px;Margin:0">
                                    {{ $title }}
                                </h1>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding:16px 0 24px">
                                <p align="center" style="font-size:14px;line-height:20px;color:#767676;Margin:0">
                                    {{ $description }}
                                </p>
                            </td>
                        </tr>
                        <tr>
                            <td style="border-bottom:1px solid #e4e2e0;font-size:1px;Margin:0">&nbsp;</td>
                        </tr>
                        <tr
                            style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                            <td class="column"
                                style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;    padding: 24px 50px 16px;">
                                <table width="100%"
                                    style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                    <tbody
                                        style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                        @foreach ($jobs as $job)
                                            <tr
                                                style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                                <td align="left"
                                                    style="padding: 0;margin: 0;border: none;border-spacing: 0;border-collapse: collapse;vertical-align: top;">
                                                    <table width="100%" cellpadding="0" cellspacing="0">
                                                        <tbody>
                                                            <tr>
                                                                <td style="padding:0 0 4px" align="left"><a
                                                                        href="{{ route('common.job-detail', ['id' => $job->slug]) }}"
                                                                        style="display:block;text-decoration:underline;font-size:16px;line-height:20px;font-weight:bold;color:#2d2d2d"
                                                                        target="_blank">
                                                                        {{ $job->job_title }} - {{ $job->job_location }}
                                                                    </a>
                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td align="left"
                                                                    style="font-size:14px;line-height:20px;font-weight:400;color:#2d2d2d;padding:0 0 4px">
                                                                    <span
                                                                        style="font-size:14px;line-height:20px;font-weight:700;color:#2d2d2d">{{ $job->company_name }}</span><span
                                                                        style="display:inline!important;color:#767676;font-size:14px">-
                                                                        {{ $job->job_location }}</span>
                                                                </td>
                                                            </tr>
                                                            @if ($job->hide_salary != 'yes')
                                                                <tr>
                                                                    <td
                                                                        style="color:#2d2d2d;font-size:14px;line-height:20px;padding:0 0 4px">
                                                                        {{ $job->salary_currency }}{{ $job->salary_from }}
                                                                        -
                                                                        {{ $job->salary_currency }}{{ $job->salary_to }}
                                                                        per {{ $job->salary_period }}</td>
                                                                </tr>
                                                            @endif

                                                            <tr>
                                                                <td align="left"
                                                                    style="font-size:14px;line-height:20px;color:#595959;padding:0 0 4px;">

                                                                    {!! strlen(nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $job->job_details))) >= 200
                                                                        ? substr(nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $job->job_details)), 0, 200) . '...'
                                                                        : substr(nl2br(preg_replace('/(\r\n|\n|\r)/', '<br/>', $job->job_details)), 0, 200) !!}

                                                                </td>
                                                            </tr>
                                                            <tr>
                                                                <td
                                                                    style="color:#767676;font-size:14px;line-height:20px;padding:0 4px 0 0">
                                                                    {{ Carbon\Carbon::createFromTimeStamp(strtotime($job->created_at))->diffForHumans() }}
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td style="padding:0 50px">
                                                    <hr style="border-color: #f10c37; ">
                                                </td>
                                            </tr>
                                        @endforeach

                                        <tr>
                                            <td>
                                                <table cellpadding="0" cellspacing="0"
                                                    style="width:100%;border:0;border-spacing:0">
                                                    <tbody>
                                                        <tr>
                                                            <td valign="top">
                                                                <div align="center"> <a
                                                                        href="{{ route('common.job-listing') }}"
                                                                        style="background-color:#f10c37;border:#ffffff solid 2px;border-radius:8px;color:#ffffff;display:inline-block;font-family:'Noto Sans',Helvetica,Arial,sans-serif;font-size:16px;font-weight:bold;line-height:48px;text-align:center;text-decoration:none;width:304px;height:48px"
                                                                        target="_blank">View all jobs</a></div>
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
                    <span> | </span>
                    <a href="{{ route('unsubscribe', ['email' => $email]) }}" title="Unsubscribe"
                        style="color:#f10c37;text-decoration:underline;font-weight: bold;" target="_blank">Unsubscribe
                    </a>
                </td>
            </tr>
        </tbody>
    </table>

</body>

</html>
