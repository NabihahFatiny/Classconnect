<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - ClassConnect</title>
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
            <div class="graduation-icon">🔐</div>
            <h1>Reset Your Password</h1>
            <p>Enter your email address or username and we'll send you a secure link to reset your password. The link will expire in 10 minutes for your security.</p>
        </div>

        <div class="right-panel">
            <div class="logo-text">ClassConnect</div>
            <h2 class="form-title">Forgot Password</h2>
            <p class="form-subtitle">Enter your email or username to receive a reset link</p>

            @if(session('status'))
                <div class="success-message">
                    <strong>{{ session('status') }}</strong>
                    @if(session('reset_link'))
                        <div style="margin-top: 15px; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 6px; word-break: break-all;">
                            <div style="font-size: 12px; margin-bottom: 8px; opacity: 0.9;">Reset Link (for testing):</div>
                            <a href="{{ session('reset_link') }}" style="color: #4CAF50; text-decoration: underline; font-size: 13px;">
                                {{ session('reset_link') }}
                            </a>
                            <div style="margin-top: 10px; font-size: 11px; opacity: 0.8;">
                                <strong>Note:</strong> In production, this link would be sent to your email address.
                                Click the link above to reset your password. The link expires in 10 minutes.
                            </div>
                        </div>
                    @endif
                </div>
            @endif

            @if(session('info'))
                <div class="success-message">{{ session('info') }}</div>
            @endif

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">📧</span>
                        <input type="text" name="email" class="form-input" placeholder="Email or Username" value="{{ old('email') }}" required autofocus>
                    </div>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="submit-button">Send Reset Link</button>

                <div class="form-links">
                    <a href="{{ route('login') }}">← Back to Login</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>


