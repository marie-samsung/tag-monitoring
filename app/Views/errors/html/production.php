<?php
$imagePath = function_exists('base_url') ? base_url('assets/images/500_server_snag.png') : '/assets/images/500_server_snag.png';
$homeUrl = function_exists('base_url') ? base_url('/') : '/';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex">
    <title><?= lang('Errors.whoops') ?></title>
    
    <!-- Premium Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --color-bg: #F5F5F7;
            --color-card: #FFFFFF;
            --color-primary: #0071E3;
            --color-primary-hover: #0077ED;
            --color-text-main: #1D1D1F;
            --color-text-secondary: #49494D;
            --color-text-muted: #86868B;
            --color-border: rgba(0, 0, 0, 0.05);
            --shadow-card: 0 12px 30px rgba(0, 0, 0, 0.04), 0 4px 12px rgba(0, 0, 0, 0.02);
            --transition-smooth: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }

        body {
            background-color: var(--color-bg);
            color: var(--color-text-main);
            font-family: 'Outfit', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            box-sizing: border-box;
        }

        .error-card {
            background-color: var(--color-card);
            border: 1px solid var(--color-border);
            border-radius: 24px;
            box-shadow: var(--shadow-card);
            padding: 48px 32px;
            max-width: 540px;
            width: 100%;
            text-align: center;
            transition: var(--transition-smooth);
        }

        .error-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.06);
        }

        @keyframes dizzy {
            0% { transform: translateY(0px) rotate(0deg); }
            25% { transform: translateY(-6px) rotate(-1.5deg); }
            50% { transform: translateY(0px) rotate(0deg); }
            75% { transform: translateY(-6px) rotate(1.5deg); }
            100% { transform: translateY(0px) rotate(0deg); }
        }

        .image-container {
            margin-bottom: 32px;
            position: relative;
            display: inline-block;
        }

        .snag-server-img {
            width: 220px;
            height: 220px;
            object-fit: contain;
            animation: dizzy 6s ease-in-out infinite;
        }

        h1 {
            font-size: 2rem;
            font-weight: 700;
            margin: 0 0 12px 0;
            letter-spacing: -0.5px;
            color: var(--color-text-main);
        }

        .error-subtitle {
            color: var(--color-text-secondary);
            font-size: 1.05rem;
            line-height: 1.6;
            margin: 0 auto 24px auto;
            max-width: 440px;
            font-weight: 400;
        }

        .funny-checklist {
            background-color: var(--color-bg);
            border-radius: 16px;
            padding: 20px;
            margin: 0 auto 32px auto;
            max-width: 400px;
            text-align: left;
            list-style: none;
            border: 1px solid var(--color-border);
        }

        .funny-checklist li {
            font-size: 0.92rem;
            color: var(--color-text-secondary);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            font-weight: 500;
        }

        .funny-checklist li:last-child {
            margin-bottom: 0;
        }

        .bullet-icon {
            font-size: 1.1rem;
            margin-right: 12px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
        }

        .button-group {
            display: flex;
            gap: 12px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn {
            font-family: inherit;
            font-size: 0.95rem;
            font-weight: 600;
            text-decoration: none;
            padding: 12px 28px;
            border-radius: 9999px;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            cursor: pointer;
            transition: var(--transition-smooth);
            border: none;
            box-sizing: border-box;
        }

        .btn-primary {
            background-color: var(--color-primary);
            color: #FFFFFF;
        }

        .btn-primary:hover {
            background-color: var(--color-primary-hover);
            transform: scale(1.02);
        }

        .btn-primary:active {
            transform: scale(0.98);
        }

        .btn-secondary {
            background-color: #E8E8ED;
            color: var(--color-text-main);
        }

        .btn-secondary:hover {
            background-color: #D1D1D6;
            transform: scale(1.02);
        }

        .btn-secondary:active {
            transform: scale(0.98);
        }

        .footer {
            margin-top: 32px;
            font-size: 0.78rem;
            color: var(--color-text-muted);
            border-top: 1px solid var(--color-border);
            padding-top: 16px;
        }
    </style>
</head>
<body>
    <div class="error-card">
        <div class="image-container">
            <img class="snag-server-img" src="<?= esc($imagePath) ?>" alt="Server Snag Character">
        </div>
        
        <h1>Whoops! Server Tripped on a Tag!</h1>
        
        <p class="error-subtitle">
            We seem to have hit a snag. The database cables got tangled or a PHP process fired when it shouldn't have. Don't worry, our monitoring tags are on it!
        </p>

        <ul class="funny-checklist">
            <li><span class="bullet-icon">🔌</span> Try refreshing the page (unplug/plug it back in).</li>
            <li><span class="bullet-icon">🏠</span> Go back to the dashboard and pretend you saw nothing.</li>
            <li><span class="bullet-icon">☕</span> Make a cup of coffee while we untangle this mess.</li>
        </ul>

        <div class="button-group">
            <a href="<?= esc($homeUrl) ?>" class="btn btn-primary">
                🏠 Go to Dashboard
            </a>
            <button onclick="history.back()" class="btn btn-secondary">
                ⬅️ Go Back
            </button>
        </div>

        <div class="footer">
            Martech Media Tagging • Samsung SDS MEA
        </div>
    </div>
</body>
</html>
