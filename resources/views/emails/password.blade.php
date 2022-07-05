<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Récupération de mot de passe</title>
    <style type="text/css">

        #outlook a {padding:0;}
        html {background:#121212; font-family: 'sans-serif'; }
        body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0;}
        .ExternalClass {width:100%;}
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}
        #backgroundTable {margin:0; padding:0; width:100% !important; line-height: 100% !important;}
        img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
        a img {border:none;}
        .image_fix {display:block;}
        table td {border-collapse: collapse;}
        table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
        a {color: #70c8b7;}

        .Logo .wkn {
            width: 54px;
        }
        .Logo span {
            display: block;
            text-transform: uppercase;
            font-weight: 300;
            letter-spacing: 0.1em;
            font-size: 18px;
            line-height: 1;
            margin-top: 2px;
            color: #000;
            font-family: sans-serif;
        }

        @media only screen and (max-device-width: 767px) {

            .padding--outer {
                padding:5px!important;
            }

            .padding--header, .padding--footer {
                padding:20px!important;
            }

            .padding--header a {
                display:block;
                width:100px;
                margin:0;
            }

            .padding--header img {
                max-width:100%!important;
                height:auto;
            }

            .padding--content {
                padding:40px!important;
            }

            .content--title {
                font-size:20px!important;
            }

        }


    </style>
    <!--[if IEMobile 7]>
    <style type="text/css">
        /* Targeting Windows Mobile */
    </style>
    <![endif]-->
    <!--[if gte mso 9]>
    <style>
        /* Target Outlook 2007 and 2010 */
    </style>
    <![endif]-->
</head>
<body style="background:#121212;">
<table cellpadding="0" cellspacing="0" border="0" id="backgroundTable" bgcolor="#121212">
    <tr>
        <td align="center" style="padding:20px;" class="padding--outer">

            <div style="max-width:700px; border-radius: 12px; overflow: hidden;">

                <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="white">
                    <tr>
                        <td valign="top" align="left" style="padding:30px;" class="padding--header">
                            <a href="#" style="text-decoration: none; display:inline-block;">
                                <div class="Logo">
                                    <div class="Wkn">
                                            <h1>Resources <span>Relationnelles</span></h1>
                                    </div>
                                </div>
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td bgcolor="#f3f5f8" height="1" style="font-size:1px;"></td>
                    </tr>
                </table>

                <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="white">
                    <tr>
                        <td style="padding:60px 40px;" class="padding--content">

                            <table width="100%" cellpadding="0" cellspacing="0" border="0" align="center" bgcolor="white">
                                <tr>
                                    <td>
                                        <font face="arial" size="4" color="#2b373d" style="font-size:24px;" class="content--title"><strong>Bienvenue.</strong></font>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="30"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <font face="arial" size="2" color="#2b373d" style="font-size:14px;">
                                            Bonjour, <br/><br/>
                                            Il y a eu une demande pour changer votre mot de passe !<br/>
                                            Si vous n'avez pas fait cette demande, veuillez ignorer cet e-mail. <br>
                                            Sinon, veuillez cliquer sur ce lien pour changer votre mot de passe:
                                            {{--                                            {{ __('mails.reset.hi') }}<br>--}}
                                            {{--                                            {{ __('mails.reset.asked') }}<br>--}}
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="30"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <font face="arial" size="2" color="#2b373d" style="font-size:14px;">
                                            {{--                                            {{ __('mails.reset.link') }} --}}
                                            <a href='{{ $reset_link }}'><font color="#70c8b7">{{ $reset_link }}</font></a><br>
                                        </font>
                                    </td>
                                </tr>
                                <tr>
                                    <td height="30"></td>
                                </tr>
                                <tr>
                                    <td>
                                        <font face="arial" size="2" color="#2b373d" style="font-size:14px;">
                                            {{--                                            {{ __('mails.reset.team') }}<br>--}}
                                            {{--                                            {{ __('mails.reset.scriptum') }}--}}
                                            L'équipe de Ressources Relationnelles.
                                        </font>
                                    </td>
                                </tr>
                            </table>

                        </td>
                    </tr>
                </table>

            </div>

        </td>
    </tr>
</table>
</body>
</html>
