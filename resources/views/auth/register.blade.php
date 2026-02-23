<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - ClassConnect</title>
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
            max-width: 500px;
            width: 100%;
            background: white;
            border-radius: 10px;
            padding: 40px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .logo-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
            justify-content: center;
        }

        .logo-icon {
            font-size: 32px;
        }

        .logo-text {
            font-family: 'Brush Script MT', cursive;
            font-size: 32px;
            color: #000;
        }

        .form-title {
            font-size: 28px;
            font-weight: bold;
            color: #000;
            margin-bottom: 30px;
            text-align: center;
        }

        .form-group {
            margin-bottom: 20px;
            position: relative;
        }

        .form-label {
            display: block;
            font-size: 14px;
            color: #333;
            margin-bottom: 8px;
            font-weight: 500;
        }

        .required {
            color: #dc3545;
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
            background: #f9f9f9;
            color: #333;
        }

        .form-input:focus {
            outline: none;
            border-color: #6B7F5E;
            background: white;
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
            background: #f9f9f9;
            color: #333;
            appearance: none;
            cursor: pointer;
        }

        .form-select:focus {
            outline: none;
            border-color: #6B7F5E;
            background: white;
        }

        .select-arrow {
            position: absolute;
            right: 15px;
            color: #666;
            pointer-events: none;
        }

        .register-button {
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

        .register-button:hover {
            background: #333;
        }

        .error-message {
            color: #dc3545;
            font-size: 13px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .success-message {
            color: #28a745;
            font-size: 13px;
            margin-top: 5px;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .help-text {
            color: #666;
            font-size: 12px;
            margin-top: 5px;
            font-style: italic;
        }

        .form-input.error {
            border-color: #dc3545;
            background: #fff5f5;
        }

        .form-input.success {
            border-color: #28a745;
            background: #f0fff4;
        }

        .form-input.validating {
            border-color: #ffc107;
        }

        .password-strength {
            margin-top: 8px;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
            overflow: hidden;
        }

        .password-strength-bar {
            height: 100%;
            transition: width 0.3s, background 0.3s;
            width: 0;
        }

        .password-strength.weak .password-strength-bar {
            width: 33%;
            background: #dc3545;
        }

        .password-strength.medium .password-strength-bar {
            width: 66%;
            background: #ffc107;
        }

        .password-strength.strong .password-strength-bar {
            width: 100%;
            background: #28a745;
        }

        .field-status {
            position: absolute;
            right: 15px;
            font-size: 18px;
            pointer-events: none;
        }

        .field-status.valid {
            color: #28a745;
        }

        .field-status.invalid {
            color: #dc3545;
        }

        .field-status.validating {
            color: #ffc107;
        }

        .progress-indicator {
            margin-bottom: 20px;
            padding: 15px;
            background: #f8f9fa;
            border-radius: 8px;
            font-size: 14px;
            color: #666;
        }

        .progress-steps {
            display: flex;
            gap: 10px;
            margin-top: 10px;
        }

        .progress-step {
            flex: 1;
            height: 4px;
            background: #e0e0e0;
            border-radius: 2px;
        }

        .progress-step.completed {
            background: #28a745;
        }

        .progress-step.active {
            background: #ffc107;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #6B7F5E;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="logo-header">
            <div class="logo-icon">🎓</div>
            <div class="logo-text">ClassConnect</div>
        </div>

        <h2 class="form-title">Sign Up</h2>

        <div class="progress-indicator">
            <div>Complete your registration in just a few steps</div>
            <div class="progress-steps">
                <div class="progress-step" id="step1"></div>
                <div class="progress-step" id="step2"></div>
                <div class="progress-step" id="step3"></div>
            </div>
        </div>

        @if($errors->any())
            <div style="background: #fff3cd; border: 2px solid #ffc107; color: #856404; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                <strong>Please fix the following errors:</strong>
                <ul style="margin: 10px 0 0 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ url('/register') }}" id="registerForm">
            @csrf

            <div class="form-group">
                <label class="form-label">Name <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" name="name" id="name" class="form-input" placeholder="Enter your full name" value="{{ old('name') }}" required>
                    <span class="field-status" id="name-status"></span>
                </div>
                <div class="help-text">Enter your first and last name as it appears on official documents</div>
                <div class="error-message" id="name-error" style="display: none;">
                    <span>⚠️</span>
                    <span id="name-error-text"></span>
                </div>
                @error('name')
                    <div class="error-message">
                        <span>⚠️</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Username <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">👤</span>
                    <input type="text" name="username" id="username" class="form-input" placeholder="Choose a unique username" value="{{ old('username') }}" required>
                    <span class="field-status" id="username-status"></span>
                </div>
                <div class="help-text">3-20 characters, letters, numbers, and underscores only</div>
                <div class="error-message" id="username-error" style="display: none;">
                    <span>⚠️</span>
                    <span id="username-error-text"></span>
                </div>
                @error('username')
                    <div class="error-message">
                        <span>⚠️</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">✉</span>
                    <input type="email" name="email" id="email" class="form-input" placeholder="your.email@example.com" value="{{ old('email') }}" required>
                    <span class="field-status" id="email-status"></span>
                </div>
                <div class="help-text">We'll use this to send you important updates</div>
                <div class="error-message" id="email-error" style="display: none;">
                    <span>⚠️</span>
                    <span id="email-error-text"></span>
                </div>
                @error('email')
                    <div class="error-message">
                        <span>⚠️</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Mobile Phone <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">📞</span>
                    <input type="tel" name="mobile_phone" id="mobile_phone" class="form-input" placeholder="e.g., 0123456789" value="{{ old('mobile_phone') }}" required>
                    <span class="field-status" id="mobile_phone-status"></span>
                </div>
                <div class="help-text">Enter your phone number without spaces or dashes</div>
                <div class="error-message" id="mobile_phone-error" style="display: none;">
                    <span>⚠️</span>
                    <span id="mobile_phone-error-text"></span>
                </div>
                @error('mobile_phone')
                    <div class="error-message">
                        <span>⚠️</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Date of Birth <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">📅</span>
                    <input type="date" name="date_of_birth" id="date_of_birth" class="form-input" value="{{ old('date_of_birth') }}" max="{{ date('Y-m-d', strtotime('-1 day')) }}" required>
                    <span class="field-status" id="date_of_birth-status"></span>
                </div>
                <div class="help-text">Select your date of birth (must be in the past)</div>
                <div class="error-message" id="date_of_birth-error" style="display: none;">
                    <span>⚠️</span>
                    <span id="date_of_birth-error-text"></span>
                </div>
                @error('date_of_birth')
                    <div class="error-message">
                        <span>⚠️</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Password <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">🔒</span>
                    <input type="password" name="password" id="password" class="form-input" placeholder="Create a strong password" required>
                    <span class="field-status" id="password-status"></span>
                </div>
                <div class="help-text">Minimum 6 characters. Use a mix of letters and numbers for better security</div>
                <div class="password-strength" id="password-strength">
                    <div class="password-strength-bar"></div>
                </div>
                <div class="error-message" id="password-error" style="display: none;">
                    <span>⚠️</span>
                    <span id="password-error-text"></span>
                </div>
                @error('password')
                    <div class="error-message">
                        <span>⚠️</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">User ID <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">📁</span>
                    <input type="text" name="user_id" id="user_id" class="form-input" placeholder="Enter your unique user ID" value="{{ old('user_id') }}" required>
                    <span class="field-status" id="user_id-status"></span>
                </div>
                <div class="help-text">Your unique identifier (provided by your institution)</div>
                <div class="error-message" id="user_id-error" style="display: none;">
                    <span>⚠️</span>
                    <span id="user_id-error-text"></span>
                </div>
                @error('user_id')
                    <div class="error-message">
                        <span>⚠️</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <div class="form-group">
                <label class="form-label">Select User</label>
                <div class="input-wrapper">
                    <span class="input-icon">▼</span>
                    <select name="user_type" id="user_type" class="form-select">
                        <option value="">Select User</option>
                        <option value="student" {{ old('user_type') == 'student' ? 'selected' : '' }}>Student</option>
                        <option value="lecturer" {{ old('user_type') == 'lecturer' ? 'selected' : '' }}>Lecturer</option>
                    </select>
                    <span class="select-arrow">▼</span>
                </div>
                @error('user_type')
                    <div class="error-message">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group" id="class-field" style="display: none;">
                <label class="form-label">Class <span class="required">*</span></label>
                <div class="input-wrapper">
                    <span class="input-icon">🏫</span>
                    <select name="class" id="class" class="form-select">
                        <option value="">Select Class</option>
                        <option value="1A" {{ old('class') == '1A' ? 'selected' : '' }}>1A</option>
                        <option value="1B" {{ old('class') == '1B' ? 'selected' : '' }}>1B</option>
                    </select>
                    <span class="select-arrow">▼</span>
                </div>
                <div class="help-text">Select your class section</div>
                <div class="error-message" id="class-error" style="display: none;">
                    <span>⚠️</span>
                    <span id="class-error-text"></span>
                </div>
                @error('class')
                    <div class="error-message">
                        <span>⚠️</span>
                        <span>{{ $message }}</span>
                    </div>
                @enderror
            </div>

            <button type="submit" class="register-button" id="submitBtn">
                <span id="submitText">Register</span>
                <span id="submitLoader" style="display: none;">Processing...</span>
            </button>

            <div class="back-link">
                <a href="{{ route('login') }}">Already have an account? Login</a>
            </div>
        </form>
    </div>

    <script>
        // Inline validation for smooth registration experience
        (function() {
            const form = document.getElementById('registerForm');
            const fields = {
                name: { element: document.getElementById('name'), validator: validateName },
                username: { element: document.getElementById('username'), validator: validateUsername, checkUnique: true },
                email: { element: document.getElementById('email'), validator: validateEmail, checkUnique: true },
                mobile_phone: { element: document.getElementById('mobile_phone'), validator: validatePhone },
                date_of_birth: { element: document.getElementById('date_of_birth'), validator: validateDateOfBirth },
                password: { element: document.getElementById('password'), validator: validatePassword },
                user_id: { element: document.getElementById('user_id'), validator: validateUserId, checkUnique: true },
            };

            let validationTimeouts = {};
            let uniqueCheckTimeouts = {};

            // Real-time validation on input
            Object.keys(fields).forEach(fieldName => {
                const field = fields[fieldName];
                field.element.addEventListener('input', function() {
                    clearTimeout(validationTimeouts[fieldName]);
                    validationTimeouts[fieldName] = setTimeout(() => {
                        validateField(fieldName, field);
                    }, 300); // Debounce validation
                });

                field.element.addEventListener('blur', function() {
                    validateField(fieldName, field);
                });
            });

            // Form submission validation
            form.addEventListener('submit', function(e) {
                let isValid = true;
                Object.keys(fields).forEach(fieldName => {
                    const field = fields[fieldName];
                    if (!validateField(fieldName, field)) {
                        isValid = false;
                    }
                });

                if (!isValid) {
                    e.preventDefault();
                    showError('Please fix all errors before submitting.');
                    return false;
                }

                // Show loading state
                document.getElementById('submitBtn').disabled = true;
                document.getElementById('submitText').style.display = 'none';
                document.getElementById('submitLoader').style.display = 'inline';
            });

            function validateField(fieldName, field) {
                const value = field.element.value.trim();
                const statusEl = document.getElementById(fieldName + '-status');
                const errorEl = document.getElementById(fieldName + '-error');
                const errorTextEl = document.getElementById(fieldName + '-error-text');

                // Clear previous state
                field.element.classList.remove('error', 'success', 'validating');
                statusEl.className = 'field-status';
                errorEl.style.display = 'none';

                // Empty field check
                if (!value) {
                    if (field.element.hasAttribute('required')) {
                        showFieldError(fieldName, field, 'This field is required.');
                        return false;
                    }
                    return true;
                }

                // Show validating state
                field.element.classList.add('validating');
                statusEl.className = 'field-status validating';
                statusEl.textContent = '⏳';

                // Validate field
                const validation = field.validator(value);

                if (!validation.valid) {
                    showFieldError(fieldName, field, validation.message);
                    return false;
                }

                // Check uniqueness if needed
                if (field.checkUnique && validation.valid) {
                    checkUniqueness(fieldName, value, field);
                } else {
                    showFieldSuccess(fieldName, field);
                }

                return true;
            }

            function showFieldError(fieldName, field, message) {
                const statusEl = document.getElementById(fieldName + '-status');
                const errorEl = document.getElementById(fieldName + '-error');
                const errorTextEl = document.getElementById(fieldName + '-error-text');

                field.element.classList.remove('success', 'validating');
                field.element.classList.add('error');
                statusEl.className = 'field-status invalid';
                statusEl.textContent = '❌';
                errorTextEl.textContent = message;
                errorEl.style.display = 'flex';
                updateProgress();
            }

            function showFieldSuccess(fieldName, field) {
                const statusEl = document.getElementById(fieldName + '-status');
                const errorEl = document.getElementById(fieldName + '-error');

                field.element.classList.remove('error', 'validating');
                field.element.classList.add('success');
                statusEl.className = 'field-status valid';
                statusEl.textContent = '✅';
                errorEl.style.display = 'none';
                updateProgress();
            }

            function checkUniqueness(fieldName, value, field) {
                clearTimeout(uniqueCheckTimeouts[fieldName]);

                uniqueCheckTimeouts[fieldName] = setTimeout(() => {
                    // Use relative URL to avoid mixed content issues
                    const url = `${window.location.origin}/api/check-unique?field=${fieldName}&value=${encodeURIComponent(value)}`;
                    fetch(url, {
                        method: 'GET',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.unique) {
                            showFieldSuccess(fieldName, field);
                        } else {
                            const fieldLabel = fieldName.replace(/_/g, ' ');
                            showFieldError(fieldName, field, data.message || `This ${fieldLabel} is already taken.`);
                        }
                    })
                    .catch(() => {
                        // If API fails, just show success (server will validate)
                        showFieldSuccess(fieldName, field);
                    });
                }, 500);
            }

            // Validation functions
            function validateName(value) {
                if (value.length < 2) return { valid: false, message: 'Name must be at least 2 characters.' };
                if (value.length > 255) return { valid: false, message: 'Name cannot exceed 255 characters.' };
                if (!/^[a-zA-Z\s'-]+$/.test(value)) return { valid: false, message: 'Name can only contain letters, spaces, hyphens, and apostrophes.' };
                return { valid: true };
            }

            function validateUsername(value) {
                if (value.length < 3) return { valid: false, message: 'Username must be at least 3 characters.' };
                if (value.length > 20) return { valid: false, message: 'Username cannot exceed 20 characters.' };
                if (!/^[a-zA-Z0-9_]+$/.test(value)) return { valid: false, message: 'Username can only contain letters, numbers, and underscores.' };
                return { valid: true };
            }

            function validateEmail(value) {
                const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!emailRegex.test(value)) return { valid: false, message: 'Please enter a valid email address.' };
                if (value.length > 255) return { valid: false, message: 'Email cannot exceed 255 characters.' };
                return { valid: true };
            }

            function validatePhone(value) {
                const phoneRegex = /^[0-9]{10,15}$/;
                if (!phoneRegex.test(value.replace(/[\s-]/g, ''))) return { valid: false, message: 'Please enter a valid phone number (10-15 digits).' };
                return { valid: true };
            }

            function validateDateOfBirth(value) {
                const date = new Date(value);
                const today = new Date();
                today.setHours(0, 0, 0, 0);
                if (date >= today) return { valid: false, message: 'Date of birth must be in the past.' };
                return { valid: true };
            }

            function validatePassword(value) {
                if (value.length < 6) return { valid: false, message: 'Password must be at least 6 characters.' };

                // Update password strength indicator
                updatePasswordStrength(value);
                return { valid: true };
            }

            function validateUserId(value) {
                if (value.length < 3) return { valid: false, message: 'User ID must be at least 3 characters.' };
                if (value.length > 255) return { valid: false, message: 'User ID cannot exceed 255 characters.' };
                return { valid: true };
            }

            function updatePasswordStrength(password) {
                const strengthEl = document.getElementById('password-strength');
                let strength = 'weak';

                if (password.length >= 8 && /[a-zA-Z]/.test(password) && /[0-9]/.test(password)) {
                    strength = 'strong';
                } else if (password.length >= 6) {
                    strength = 'medium';
                }

                strengthEl.className = 'password-strength ' + strength;
            }

            function updateProgress() {
                const totalFields = Object.keys(fields).length;
                let completedFields = 0;

                Object.keys(fields).forEach(fieldName => {
                    const field = fields[fieldName];
                    if (field.element.classList.contains('success')) {
                        completedFields++;
                    }
                });

                const progress = Math.min(completedFields / totalFields, 1);
                const steps = document.querySelectorAll('.progress-step');

                steps.forEach((step, index) => {
                    step.classList.remove('completed', 'active');
                    if (progress > (index + 1) / steps.length) {
                        step.classList.add('completed');
                    } else if (progress > index / steps.length) {
                        step.classList.add('active');
                    }
                });
            }

            function showError(message) {
                // Could show a toast notification here
                console.error(message);
            }
        })();

        // Toggle class field based on user type (outside IIFE so it's globally accessible)
        function toggleClassField() {
            const userType = document.getElementById('user_type');
            const classField = document.getElementById('class-field');
            const classSelect = document.getElementById('class');

            if (!userType || !classField || !classSelect) {
                return;
            }

            if (userType.value === 'student') {
                classField.style.display = 'block';
                classSelect.setAttribute('required', 'required');
            } else {
                classField.style.display = 'none';
                classSelect.removeAttribute('required');
                classSelect.value = '';
            }
        }

        // Initialize on page load and attach event listener
        document.addEventListener('DOMContentLoaded', function() {
            const userTypeSelect = document.getElementById('user_type');
            if (userTypeSelect) {
                // Attach event listener
                userTypeSelect.addEventListener('change', toggleClassField);
                // Initialize on page load
                toggleClassField();
            }
        });
    </script>
</body>
</html>


