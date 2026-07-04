<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= esc($subject) ?></title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            background-color: #f4f7f6;
            margin: 0;
            padding: 0;
            -webkit-font-smoothing: antialiased;
        }
        .email-wrapper {
            width: 100%;
            background-color: #f4f7f6;
            padding: 30px 0;
        }
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.05);
        }
        .email-header {
            background-color: #4154f1; /* NiceAdmin primary color */
            padding: 25px 30px;
            text-align: center;
        }
        .email-header h1 {
            color: #ffffff;
            margin: 0;
            font-size: 24px;
            font-weight: 600;
            letter-spacing: 1px;
        }
        .email-body {
            padding: 40px 30px;
            color: #444444;
            line-height: 1.6;
            font-size: 16px;
        }
        .email-body p {
            margin-top: 0;
            margin-bottom: 20px;
        }
        .email-footer {
            background-color: #f9f9fa;
            padding: 20px 30px;
            text-align: center;
            border-top: 1px solid #eeeeee;
        }
        .email-footer p {
            margin: 0;
            font-size: 13px;
            color: #999999;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            background-color: #4154f1;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin-top: 10px;
        }
        .button:hover {
            background-color: #2b39d1;
        }
        .accent {
            color: #4154f1;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-wrapper">
        <div class="email-container">
            <!-- Header -->
            <div class="email-header">
                <h1>E-Library System</h1>
            </div>

            <!-- Body -->
            <div class="email-body">
                <p><strong><?= esc($subject) ?></strong></p>
                <div style="margin-top: 25px;">
                    <?= $message ?>
                </div>
            </div>

            <!-- Footer -->
            <div class="email-footer">
                <p>&copy; <?= date('Y') ?> E-Library System. Hak Cipta Dilindungi.</p>
                <p style="margin-top: 5px;">Pesan ini dikirim secara otomatis oleh sistem, mohon untuk tidak membalas.</p>
            </div>
        </div>
    </div>
</body>
</html>
