<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <title>Invoice</title>

    <style>
        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table tr td:nth-child(2) {
            text-align: right;
        }

        .invoice-box table tr.top table td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.top table td.title {
            font-size: 45px;
            line-height: 45px;
            color: #333;
        }

        .invoice-box table tr.information table td {
            padding-bottom: 40px;
        }

        .invoice-box table tr.heading td {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.details td {
            padding-bottom: 20px;
        }

        .invoice-box table tr.item td {
            border-bottom: 1px solid #eee;
        }

        .invoice-box table tr.item.last td {
            border-bottom: none;
        }

        .invoice-box table tr.total td:nth-child(2) {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        @media only screen and (max-width: 600px) {
            .invoice-box table tr.top table td {
                width: 100%;
                display: block;
                text-align: center;
            }

            .invoice-box table tr.information table td {
                width: 100%;
                display: block;
                text-align: center;
            }
        }

        /** RTL **/
        .invoice-box.rtl {
            direction: rtl;
            font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
        }

        .invoice-box.rtl table {
            text-align: right;
        }

        .invoice-box.rtl table tr td:nth-child(2) {
            text-align: left;
        }
    </style>
    <style>
        .tddescription {
            display: flex;
            justify-content: space-between;
            align-content: stretch;
            align-items: baseline;
        }

        .span-margin {
            margin-left: 65px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="6">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ asset('backend/img/job-portal-logo.png') }}"
                                    style="width: 100%; max-width: 300px" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <div class="row">
                                    <div class="tddescription">
                                        <div class="col-md-6">
                                            <div>
                                                <a href="{{ route('welcome') }}">Recruit.ie</a> <br />
                                                Address: 61 Lower Kilmacud Road <br> <span
                                                    class="span-margin">Stillorgan</span>, <span>Co. Dublin</span>,
                                                <span>A94 A2F7</span> <br> <span class="span-margin">Ireland</span>
                                                <br />
                                                Phone Number: <a href="tel:01 215 0518">01 215 0518</a> <br />
                                                Email: <a
                                                    href="mailto:{{ $settings->contact_email }}">{{ $settings->contact_email }}</a>
                                                <br />
                                                VAT Number: 3418775vh <br>
                                            </div>
                                        </div>
                                        <div class="col-md-6" style="text-align: right;">
                                            <div>
                                                Invoice #: {{ $invoice->transaction_id }}<br />
                                                Created:
                                                {{ Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('jS F Y') }}<br />
                                                Payment:
                                                {{ Carbon::createFromTimeStamp(strtotime($invoice->created_at))->format('jS F Y') }}
                                            </div>
                                            <div style="margin-top: 12%;">
                                                {{ $invoice->user->name }}<br />
                                                {{ $invoice->user->email }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="heading">
                <td>Payment Method</td>
                <td></td>
                <td></td>
                <td style="width: 20%;text-align: right;">Online #</td>
            </tr>
            <tr class="details">
                <td>Card</td>
                <td></td>
                <td></td>
                {{-- <td style="width: 20%;text-align: right;">€{{ $invoice->amount }} including
                    {{ $subscriptions->plan->vat }}% VAT</td> --}}
            </tr>
            @if ($invoice->user->user_type != 'coach')
                <tr class="details">
                    <td style="width: 20%"></td>
                    <td style="width: 20%"></td>
                    <td style="width: 10%"></td>
                    {{-- <td style="width: 60%;text-align: right;">Subscription {{$subscriptions->plan->number_of_job_post}} Job listing per month:  €{{$subscriptions->plan->price}}</td> --}}
                </tr>
            @endif
            <tr class="heading">
                <td style="width: 20%;text-align: left;">Plan Title</td>
                <td></td>
                <td></td>
                {{-- <td style="width: 40%;text-align: right;">{{$subscriptions->plan->title}}</td> --}}
            </tr>
            <tr class="heading">
                <td style="width: 20%;text-align: left;">Subtotal: </td>
                <td></td>
                <td></td>
                {{-- <td style="width: 40%;text-align: right;">€{{$subscriptions->plan->price}}</td> --}}
            </tr>
            <tr class="heading">
                <td style="width: 20%;text-align: left;">Vat @ 23%: </td>
                <td></td>
                <td></td>
                {{-- <td style="width: 40%;text-align: right;">€{{(($subscriptions->plan->price * 23)/100)}} (round up €{{round(($subscriptions->plan->price * 23)/100)}})</td> --}}
            </tr>
            <tr class="heading">
                <td style="width: 20%;text-align: left;">Total: </td>
                <td></td>
                <td></td>
                <td style="width: 40%;text-align: right;">€{{ $invoice->amount }}</td>
            </tr>
        </table>
    </div>
</body>

</html>
