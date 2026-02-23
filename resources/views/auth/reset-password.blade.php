<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - ClassConnect</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #F2EFDF;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .container {
            display: flex;
            max-width: 1200px;
            width: 100%;
            background: white;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .left-panel {
            flex: 2;
            background: white;
            padding: 60px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .graduation-icon {
            font-size: 120px;
            margin-bottom: 30px;
            color: #000;
        }

        .left-panel h1 {
            font-size: 32px;
            font-weight: bold;
            color: #000;
            margin-bottom: 20px;
        }

        .left-panel p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            max-width: 500px;
        }

        .right-panel {
            flex: 1;
            background: #9A7A4A;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .logo-text {
            font-family: 'Brush Script MT', cursive;
            font-size: 36px;
            color: white;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-title {
            font-size: 24px;
            font-weight: bold;
            color: white;
            margin-bottom: 10px;
            text-align: center;
        }

        .form-subtitle {
            font-size: 14px;
            color: rgba(255, 255, 255, 0.9);
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .input-wrapper {
            position: relative;
            display: flex;
            align-items: center;
        }

        .input-icon {
            position: absolute;
            left: 15px;
            color: #666;
            font-size: 18px;
            z-index: 1;
        }

        .form-input {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            color: #333;
        }

        .form-input:focus {
            outline: none;
            border-color: #9A7A4A;
        }

        .form-input::placeholder {
            color: #999;
        }

        .submit-button {
            width: 100%;
            padding: 14px;
            background: #000;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 10px;
            transition: background 0.3s;
        }

        .submit-button:hover {
            background: #333;
        }

        .form-links {
            display: flex;
            justify-content: center;
            margin-top: 20px;
            font-size: 14px;
        }

        .form-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
        }

        .form-links a:hover {
            color: #ddd;
        }

        .error-message {
            color: #ffebee;
            background: rgba(220, 53, 69, 0.2);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-top: 5px;
        }

        .success-message {
            color: #e8f5e9;
            background: rgba(46, 125, 50, 0.2);
            padding: 8px 12px;
            border-radius: 6px;
            font-size: 14px;
            margin-bottom: 15px;
        }

        @media (max-width: 768px) {
            .container {
                flex-direction: column;
            }

            .left-panel {
                padding: 40px 20px;
            }

            .right-panel {
                padding: 40px 20px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="graduation-icon">🔑</div>
            <h1>Create New Password</h1>
            <p>Enter your new password below. Make sure it's at least 6 characters long and keep it secure. This reset link expires in 10 minutes and can only be used once.</p>
        </div>

        <div class="right-panel">
            <div class="logo-text">ClassConnect</div>
            <h2 class="form-title">Reset Password</h2>
            <p class="form-subtitle">Enter your new password</p>

            @if($errors->any())
                <div class="error-message">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('password.reset.submit') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">📧</span>
                        <input type="email" name="email" class="form-input" placeholder="Email Address" value="{{ old('email', $email) }}" required autofocus>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password" class="form-input" placeholder="New Password" required>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password" name="password_confirmation" class="form-input" placeholder="Confirm New Password" required>
                    </div>
                </div>

                <button type="submit" class="submit-button">Reset Password</button>

                <div class="form-links">
                    <a href="{{ route('login') }}">← Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


