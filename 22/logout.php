<?php
require_once 'config.php';

// Check if session is active before trying to destroy it
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Clear all session variables first
$_SESSION = array();

// Destroy the session cookie if it exists
if (isset($_COOKIE[session_name()])) {
    setcookie(session_name(), '', time()-3600, '/');
}

// Now destroy the session
session_destroy();

// Start a new session for the redirect message
session_start();
?>
<!DOCTYPE html>
<html lang="ku">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>دەرچوون - Logout</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .logout-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            padding: 3rem;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 450px;
            width: 100%;
            border: 1px solid rgba(255, 255, 255, 0.2);
            animation: fadeIn 0.6s ease-out;
        }
        
        .logout-icon {
            font-size: 48px;
            margin-bottom: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }
        
        .logout-message {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 10px;
            font-weight: 600;
        }
        
        .logout-submessage {
            color: #666;
            margin-bottom: 30px;
            font-size: 1rem;
        }
        
        .redirect-info {
            background: rgba(102, 126, 234, 0.1);
            color: #2c3e50;
            padding: 1.5rem;
            border-radius: 15px;
            margin-bottom: 25px;
            font-size: 0.95rem;
            border: 1px solid rgba(102, 126, 234, 0.2);
        }
        
        .countdown {
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            font-size: 1.2rem;
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.8rem 1.5rem;
            border: none;
            border-radius: 25px;
            font-size: 1rem;
            font-weight: 500;
            cursor: pointer;
            text-decoration: none;
            transition: all 0.3s ease;
            min-width: 160px;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }
        
        .btn-secondary {
            background: linear-gradient(135deg, #718096 0%, #4a5568 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(74, 85, 104, 0.3);
        }
        
        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        }
        
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @media (max-width: 480px) {
            .logout-container {
                margin: 1rem;
                padding: 2rem;
            }
            
            .btn {
                width: 100%;
                margin: 0.5rem 0;
            }
            
            .btn-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="logout-container">
        <div class="logout-icon">
            <i class="fas fa-sign-out-alt"></i>
        </div>
        <h1 class="logout-message">بە سەرکەوتوویی دەرچوویت</h1>
        <p class="logout-submessage">Successfully logged out</p>
        
        <div class="redirect-info">
            <p>دوای <span class="countdown" id="countdown">5</span> چرکە بە خۆکاری دەگەڕێیتەوە بۆ ماڵپەڕەکە</p>
            <p>Redirecting to homepage in <span class="countdown" id="countdown2">5</span> seconds...</p>
        </div>
        
        <div class="btn-group">
            <a href="index.php" class="btn btn-primary">
                <i class="fas fa-home"></i> گەڕانەوە بۆ ماڵپەڕەکە
            </a>
            <a href="login.php" class="btn btn-secondary">
                <i class="fas fa-sign-in-alt"></i> چوونە ژوورەوە دووبارە
            </a>
        </div>
    </div>
    
    <script>
        let countdown = 5;
        const countdownElements = document.querySelectorAll('.countdown');
        
        const timer = setInterval(() => {
            countdown--;
            countdownElements.forEach(el => {
                el.textContent = countdown;
            });
            
            if (countdown <= 0) {
                clearInterval(timer);
                window.location.href = 'index.php';
            }
        }, 1000);
        
        document.querySelectorAll('a').forEach(link => {
            link.addEventListener('click', () => {
                clearInterval(timer);
            });
        });
    </script>
</body>
</html>