<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>{{ config('site.name') }}</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <link href='http://fonts.googleapis.com/css?family=Raleway:400,500,700,300,600' rel='stylesheet' type='text/css' />
        <style type="text/css">
            body, #body_style {
                width: 100% !important;
                background: #ddd;
                font-family: 'Raleway', Arial, Helvetica, sans-serif;
                color: #414042;
                line-height: 1;
            }

            .ExternalClass {
                width: 100%;
            }
            .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {
                line-height: 100%;
            }

            body {
                -webkit-text-size-adjust: none;
                -ms-text-size-adjust: none;
            }

            body, img, div, p, ul, li, span, strong, a {
                margin: 0;
                padding: 0;
            }

            table {
                border-spacing: 0;
            }

            table td {
                border-collapse: collapse;
            }

            a {
                color: #ffd204;
                text-decoration: underline;
                outline: none;
            }
            a:hover {
                text-decoration: none !important;
            }

            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                color: #ffd204;
            }

            img {
                display: block;
                border: none;
                outline: none;
                text-decoration: none;
            }

            table {
                border-collapse: collapse;
                mso-table-lspace: 0pt;
                mso-table-rspace: 0pt;
            }

            /*Style for Page design Start Here*/
            @media screen and (max-width: 599px) {
                body[yahoo] .wrapper-section-one, .content-block img {
                    width: 100% !important;
                }
                body[yahoo] .menu-space {
                    width: auto !important;
                }
                body[yahoo] .content-block {
                    width: 100% !important;
                    display: block;
                }

                body[yahoo] .content-block2 {
                    max-width: 100% !important;
                }

            }

        </style>

    </head>

    <body style="font-family: 'Raleway',Arial,Helvetica,sans-serif; font-size: 14px; color: #414042; background: #ddd; margin: 0; width:100% !important; " yahoo="fix">

        <!--Section Starts here-->
        <table width="600" border="0" cellspacing="0" cellpadding="0" align="center" class="wrapper-section-one" style="background: #fff;" bgcolor="#fff">
            <tr>
                <td>
                <table cellspacing="0" cellpadding="0" border="0" width="100%" >
                    <tr>
                        <td width="24">&nbsp;</td>
                        <td>
                            <table  cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailHeader">
                                            <tr>
                                                <td>
                                                    歡迎您加入成為 e-BABY 的成員。<br />
                                                    您可以使用瀏覽器連接到 http://crm.e-baby.com.tw/ 更改您的個人資料。<br />
                                                    (如果您無法連到伺服器，請與管理者連絡。)
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="5" cellspacing="0" id="emailBody">

                                            @if (isset($帳號))
                                            <tr>
                                                <th align="right" valign="top" style="white-space: nowrap;">
                                                    您的使用者帳號：
                                                </th>
                                                <td valign="top">
                                                    {{ $帳號 }}
                                                </td>
                                            </tr>
                                            @endif

                                            @if (isset($密碼))
                                            <tr>
                                                <th align="right" valign="top" style="white-space: nowrap;">
                                                    您的密碼：
                                                </th>
                                                <td valign="top">
                                                    {{ $密碼 }}
                                                </td>
                                            </tr>
                                            @endif

                                        </table>
                                    </td>
                                </tr>

                                <tr>
                                    <td align="center" valign="top">
                                        <table border="0" cellpadding="5" cellspacing="0" width="100%" id="emailFooter" style="margin-top:20px; margin-bottom: 20px;">
                                            <tr>
                                                <td>
                                                    {{ config('site.name') }}
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                        <td width="24">&nbsp;</td>
                    </tr>
                </table></td>
            </tr>
        </table>
        <!--Section end here-->

    </body>
</html>
