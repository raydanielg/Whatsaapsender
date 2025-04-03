<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www..w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html lang="en" xmlns="http://www..w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <link rel="icon" type="image/svg+xml" href>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html charset=utf-8"/>
    <meta http-equiv="X-UA-Compatibe" content="IE=edge"/>
    <title>{{getArrayValue($setting->appearance, 'site_title')}}</title>
    <style type="text/css">
        @import url('https://fonts.googleapis.com/css2?family=Inter&family=Open+Sans&family=Manrope&display=swap');
        body {
            margin: 0;
            font-family: 'Manrope', sans-serif;
            line-height: 1.5rem;
        }
        table {
            border-spacing: 0;
        }
        td {
            padding: 0;
        }
        img {
            border: 0;
        }
    </style>
</head>

<body>
<div style="width: 100%; table-layout: fixed;  background-color: #F4FAFA; min-height: 100vh; border-spacing: 0;">
    <table width="100%">
        <tr>
            <td>
                <table style="width: 100%;">
                    <tr>
                        <td>
                            <table style=" background-repeat: no-repeat;width: 100%; min-height: 8rem;background-size: cover;">
                                <tr>
                                    <td align="center">
                                        <span>
                                            <img src="{{showImage(filePath()['logo']['path'].'/logo.png')}}" alt="Review Logo"></span>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <table align="center" style="border-radius: 10px; background-color:#FFFFFF; width: 600px; min-width: 400px; ">
                                <tr>
                                    <td align="center" style="padding: 20px;">
                                       @php echo $content @endphp
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</div>
</body>
</html>
