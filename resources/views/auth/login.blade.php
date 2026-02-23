<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - ClassConnect</title>
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
            color: #333;
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
            padding: 16px 15px 16px 45px;
            border: 2px solid #ddd;
            border-radius: 10px;
            font-size: 16px;
            background: white;
            color: #333;
            -webkit-appearance: none;
            appearance: none;
            min-height: 48px; /* Minimum touch target size */
        }

        .form-input:focus {
            outline: none;
            border-color: #9A7A4A;
            box-shadow: 0 0 0 3px rgba(154, 122, 74, 0.1);
        }

        .form-input::placeholder {
            color: #999;
        }

        .form-select {
            width: 100%;
            padding: 12px 15px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: white;
            color: #333;
            appearance: none;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: #9A7A4A;
        }

        .login-button {
            width: 100%;
            padding: 16px;
            background: #000;
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 18px;
            font-weight: 600;
            cursor: pointer;
            margin-top: 15px;
            transition: background 0.3s, transform 0.1s;
            min-height: 52px; /* Larger touch target for mobile */
            -webkit-tap-highlight-color: transparent;
        }

        .login-button:hover {
            background: #333;
        }

        .login-button:active {
            transform: scale(0.98);
            background: #222;
        }

        .form-links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 14px;
            flex-wrap: wrap;
            gap: 10px;
        }

        .form-links a {
            color: white;
            text-decoration: none;
            transition: color 0.3s;
            padding: 8px 0;
            min-height: 44px; /* Touch target size */
            display: flex;
            align-items: center;
            -webkit-tap-highlight-color: transparent;
        }

        .form-links a:hover {
            color: #ddd;
        }

        .form-links a:active {
            opacity: 0.7;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        /* Mobile Optimizations */
        @media (max-width: 768px) {
            body {
                padding: 10px;
                align-items: flex-start;
                padding-top: 20px;
            }

            .container {
                flex-direction: column;
                max-width: 100%;
                border-radius: 15px;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            }

            .left-panel {
                display: none; /* Hide left panel on mobile for faster login */
            }

            .right-panel {
                flex: 1;
                padding: 30px 25px;
                width: 100%;
            }

            .logo-text {
                font-size: 32px;
                margin-bottom: 25px;
            }

            .form-title {
                font-size: 22px;
                margin-bottom: 25px;
                color: white;
            }

            .form-group {
                margin-bottom: 18px;
            }

            .form-input,
            .form-select {
                font-size: 16px; /* Prevents zoom on iOS */
                padding: 18px 15px 18px 50px;
            }

            .input-icon {
                left: 18px;
                font-size: 20px;
            }

            .login-button {
                font-size: 18px;
                padding: 18px;
                margin-top: 20px;
            }

            .form-links {
                flex-direction: column;
                gap: 15px;
                margin-top: 25px;
            }

            .form-links a {
                text-align: center;
                justify-content: center;
                font-size: 15px;
            }
        }

        /* Small mobile devices */
        @media (max-width: 480px) {
            body {
                padding: 5px;
            }

            .right-panel {
                padding: 25px 20px;
            }

            .logo-text {
                font-size: 28px;
                margin-bottom: 20px;
            }

            .form-title {
                font-size: 20px;
                margin-bottom: 20px;
            }
        }

        /* Landscape mobile */
        @media (max-width: 768px) and (orientation: landscape) {
            body {
                padding: 10px;
            }

            .right-panel {
                padding: 20px 25px;
            }

            .form-group {
                margin-bottom: 15px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <div class="graduation-icon">🎓</div>
            <h1>Empower Your Education Journey Today!</h1>
            <p>With ClassConnect, students and teachers can access classes, share materials, and communicate effortlessly. Stay updated with announcements, manage assignments, and keep your academic life organized anytime, anywhere.</p>
        </div>

        <div class="right-panel">
            <div class="logo-text">ClassConnect</div>
            <h2 class="form-title">User Login</h2>

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">👤</span>
                        <input type="text"
                               name="username"
                               id="username"
                               class="form-input"
                               placeholder="Username"
                               value="{{ old('username') }}"
                               autocomplete="username"
                               autocapitalize="none"
                               autocorrect="off"
                               spellcheck="false"
                               required
                               autofocus>
                    </div>
                    @error('username')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">🔒</span>
                        <input type="password"
                               name="password"
                               id="password"
                               class="form-input"
                               placeholder="Password"
                               autocomplete="current-password"
                               required>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <div class="input-wrapper">
                        <span class="input-icon">▼</span>
                        <select name="user_type"
                                id="user_type"
                                class="form-select"
                                required
                                autocomplete="off">
                            <option value="">Select User Type</option>
                            <option value="student" {{ old('user_type') == 'student' ? 'selected' : '' }}>Student</option>
                            <option value="lecturer" {{ old('user_type') == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                        </select>
                    </div>
                    @error('user_type')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="login-button">Login</button>

                <div class="form-links">
                    <a href="{{ route('register') }}">Create an account</a>
                    <a href="{{ route('password.request') }}">Forgot Password?</a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Optimize for mobile login - focus first field on load
        (function() {
            // Auto-focus username field on mobile for faster input
            const usernameField = document.getElementById('username');
            if (usernameField && window.innerWidth <= 768) {
                // Small delay to ensure keyboard doesn't interfere with layout
                setTimeout(() => {
                    usernameField.focus();
                }, 100);
            }

            // Prevent form resubmission on back button
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            // Optimize form submission for mobile
            const form = document.querySelector('form');
            const submitButton = document.querySelector('.login-button');

            form.addEventListener('submit', function() {
                // Disable button to prevent double submission
                submitButton.disabled = true;
                submitButton.textContent = 'Logging in...';
            });

            // Handle enter key for faster login
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Enter' && e.target.tagName !== 'BUTTON') {
                    const form = document.querySelector('form');
                    if (form.checkValidity()) {
                        form.submit();
                    }
                }
            });
        })();
    </script>
</body>
</html>


