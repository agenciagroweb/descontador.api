<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link href='http://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <style type="text/css">
        /* Based on The MailChimp Reset INLINE: Yes. */
        /* Client-specific Styles */
        #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
        body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; font-family: Arial, sans-serif;color:#424242;}
        /* Prevent Webkit and Windows Mobile platforms from changing default font sizes.*/
        .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;}

        /* Some sensible defaults for images
        Bring inline: Yes. */
        img {outline:none; text-decoration:none; -ms-interpolation-mode: bicubic;}
        a img {border:none;}
        .image_fix {display:block;}

        /* Outlook 07, 10 Padding issue fix
        Bring inline: No.*/
        table td {border-collapse: collapse;}

        /* Remove spacing around Outlook 07, 10 tables
        Bring inline: Yes */
        table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }

        /***************************************************
        ****************************************************
        MOBILE TARGETING
        ****************************************************
        ***************************************************/
        @media only screen and (max-device-width: 480px) {
            /* Part one of controlling phone number linking for mobile. */
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                pointer-events: none;
                cursor: default;
            }

            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                pointer-events: auto;
                cursor: default;
            }

        }

        /* More Specific Targeting */

        @media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
            /* You guessed it, ipad (tablets, smaller screens, etc) */
            /* repeating for the ipad */
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                pointer-events: none;
                cursor: default;
            }

            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                pointer-events: auto;
                cursor: default;
            }
        }

        @media only screen and (-webkit-min-device-pixel-ratio: 2) {
            /* Put your iPhone 4g styles in here */
        }

        /* Android targeting */
        @media only screen and (-webkit-device-pixel-ratio:.75){
            /* Put CSS for low density (ldpi) Android layouts in here */
        }
        @media only screen and (-webkit-device-pixel-ratio:1){
            /* Put CSS for medium density (mdpi) Android layouts in here */
        }
        @media only screen and (-webkit-device-pixel-ratio:1.5){
            /* Put CSS for high density (hdpi) Android layouts in here */
        }
        /* end Android targeting */

        #weare-table {
            font-family: 'Lato', Arial, sans-serif;
            color:#424242;
        }
        font { font-family: 'Lato', Arial, sans-serif; }
    </style>

    <!-- Targeting Windows Mobile -->
    <!--[if IEMobile 7]>
    <style type="text/css">

    </style>
    <![endif]-->

    <!-- ***********************************************
    ****************************************************
    END MOBILE TARGETING
    ****************************************************
    ************************************************ -->

    <!--[if gte mso 9]>
    <style>
        /* Target Outlook 2007 and 2010 */
        td {font-family:Arial, sans-serif;}
        table.MsoNormalTable{font-family:Arial, sans-serif;}
        font {font-family:Arial, sans-serif;}
    </style>
    <![endif]-->

</head>
<body>

<table id="weare-table" width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff">
    <tr>
        <td>
            <table width="560" align="center" cellpadding="0" cellspacing="0" border="0">
                <tr>
                    <td align="left" height="48" valign="bottom"><a href="http://www.weare.com"><img src="http://weareesports.com.br/forum/images/square/logo.png" alt="Weare" width="224" height="74" border="0" /></a></td>
                </tr>
                <tr>
                    <td height="70" valign="middle"><h1 style="font-family:Arial,sans-serif;color:#152e6d;font-size:25px;font-weight:bold;"><font face="Arial,sans-serif" color>Suporte:</font></h1></td>
                </tr>
                <tr>
                    <td>
                        <p>
                            Título: <?php echo $input['title']; ?><br>
                            Mensagem: <?php echo $input['message']; ?><br>
                            E-Mail do Remetente: <?php echo $input['email']; ?>
                        </p>

                        <p>
                            Usuário: <?php echo $input['user_name']; ?><br>
                            E-mail do Usuário: <?php echo $input['user_email']; ?>
                        </p>

                        <p>
                            E-mail enviado através do formulário de suporte para torneio, às <?php echo date('d/m/Y H:i'); ?>.<br><br>
                        </p>
                    </td>
                </tr>

                <tr>
                    <td style="border-top:1px solid #D0D0D0;" align="center" valign="middle" height="117">
                        <table cellpadding="0" cellspacing="0" border="0" align="center">
                            <tr>
                                <td width="36"><a href="https://www.facebook.com/weares" title="Facebook"><img src="http://www.wearesports.com.br/assets/icons/icon-facebook-blue.png" alt="Facebook" /></a></td>
                                <td width="36"><a href="https://twitter.com/wasoficial" title="Twitter"><img src="http://www.wearesports.com.br/assets/icons/icon-twitter-blue.png" alt="Twitter" /></a></td>
                                <td width="36"><a href="https://www.youtube.com/channel/UCk-cLjdVWwZtl3M6AMY9YiA" title="YouTube"><img src="http://www.wearesports.com.br/assets/icons/icon-youtube-blue.png" alt="YouTube" /></a></td>
                            </tr>
                        </table>
                        <p style="font-family:Arial,sans-serif;color:#838383;font-size:11px;"><font face="Arial,sans-serif"><a href="http://www.wearesports.com.br/" style="text-decoration:none;color:#152e6d;">www.wearesports.com.br</a><br/><br/>WE ARE SPORTS© É UMA MARCA REGISTRADA DE WE ARE ESPORTS ENTRETENIMENTO LTDA</font></p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>

</body>
</html>