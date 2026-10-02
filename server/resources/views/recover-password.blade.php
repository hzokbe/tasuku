<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="color-scheme" content="light dark">
    <meta name="supported-color-schemes" content="light dark">
    <title>Reset your password</title>
    <style>
        table {
            border-collapse: collapse;
            border-spacing: 0;
        }

        td {
            padding: 0;
        }

        body {
            margin: 0;
            padding: 0;
            width: 100%;
            background-color: #f8fafc;
        }

        a {
            color: #00a63e;
        }

        @media (prefers-color-scheme: dark) {
            body, .bg-page {
                background-color: #0f172a !important;
            }

            .bg-card {
                background-color: #1d293d !important;
                border-color: #314158 !important;
            }

            .text-default {
                color: #f1f5f9 !important;
            }

            .text-muted {
                color: #90a1b9 !important;
            }

            .border-default {
                border-color: #314158 !important;
            }

            .btn {
                background-color: #05df72 !important;
                color: #0f172a !important;
            }

            a {
                color: #05df72 !important;
            }
        }

        @media only screen and (max-width: 620px) {
            .container {
                width: 100% !important;
            }

            .content {
                padding: 24px !important;
            }
        }
    </style>
</head>
<body>
<table role="presentation" class="bg-page" style="width:100%;background-color:#f8fafc;">
    <tr>
        <td style="text-align:center;padding:40px 16px;">
            <table role="presentation" class="container"
                   style="width:600px;max-width:100%;margin:0 auto;text-align:left;border-collapse:separate;border-spacing:0;">
                <tr>
                    <td style="text-align:center;padding-bottom:24px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:20px;font-weight:700;">
                        <a href="{{ config('app.url') }}" class="text-default"
                           style="color:#0f172a;text-decoration:none;">{{ config('app.name') }}</a>
                    </td>
                </tr>
                <tr>
                    <td class="bg-card border-default"
                        style="background-color:#ffffff;border:1px solid #e2e8f0;border-radius:12px;">
                        <table role="presentation" style="width:100%;">
                            <tr>
                                <td class="content"
                                    style="padding:40px;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;">
                                    <h1 class="text-default"
                                        style="margin:0 0 16px;font-size:20px;line-height:28px;font-weight:600;color:#0f172a;">
                                        Hello, {{ $username }}!
                                    </h1>
                                    <p class="text-muted"
                                       style="margin:0 0 16px;font-size:14px;line-height:24px;color:#45556c;">
                                        We received a request to reset your password. Click the button below to choose a
                                        new one.
                                    </p>
                                    <table role="presentation" style="margin:24px 0;">
                                        <tr>
                                            <td class="btn"
                                                style="text-align:center;background-color:#00c950;border-radius:8px;">
                                                <a href="{{ $resetURL }}" target="_blank" class="btn"
                                                   style="display:inline-block;padding:10px 20px;font-size:14px;font-weight:500;line-height:20px;color:#ffffff;text-decoration:none;border-radius:8px;">Reset
                                                    password</a>
                                            </td>
                                        </tr>
                                    </table>
                                    <p class="text-muted"
                                       style="margin:0 0 16px;font-size:14px;line-height:24px;color:#45556c;">
                                        If you didn't request a password reset, you can safely ignore this email.
                                    </p>
                                    <p class="text-default"
                                       style="margin:24px 0 0;font-size:14px;line-height:24px;color:#0f172a;">
                                        Regards,<br>{{ config('app.name') }}
                                    </p>
                                    <table role="presentation" style="width:100%;margin-top:24px;">
                                        <tr>
                                            <td class="border-default"
                                                style="border-top:1px solid #e2e8f0;padding-top:24px;">
                                                <p class="text-muted"
                                                   style="margin:0;font-size:12px;line-height:20px;color:#62748e;word-break:break-all;">
                                                    If the button doesn't work, copy and paste this URL into your
                                                    browser:
                                                    <br>
                                                    <a href="{{ $resetURL }}">{{ $resetURL }}</a>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td class="text-muted"
                        style="text-align:center;padding:24px 16px 0;font-family:-apple-system,BlinkMacSystemFont,'Segoe UI',Roboto,Helvetica,Arial,sans-serif;font-size:12px;line-height:20px;color:#62748e;">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</body>
</html>
