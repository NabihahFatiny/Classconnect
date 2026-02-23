@extends('layouts.app')

@section('title', 'Change Password')

@section('content')
    <style>
        .password-container {
            max-width: 600px;
            margin: 0 auto;
            width: 100%;
        }

        .password-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
            text-align: center;
        }

        .password-card {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: 0 auto;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #333;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .password-input-wrapper {
            position: relative;
        }

        .form-input {
            width: 100%;
            padding: 12px 45px 12px 16px;
            border: 1px solid #E0E0E0;
            border-radius: 8px;
            font-size: 14px;
            background: #f9f9f9;
            color: #333;
            transition: border-color 0.2s, background 0.2s;
        }

        .password-toggle {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #666;
            font-size: 18px;
            padding: 4px 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: color 0.2s;
        }

        .password-toggle:hover {
            color: #333;
        }

        .form-input:focus {
            outline: none;
            background: white;
            border-color: #4CAF50;
        }

        .form-input::placeholder {
            color: #999;
        }

        .form-actions {
            display: flex;
            gap: 15px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn {
            padding: 12px 24px;
            border: none;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s;
        }

        .btn-primary {
            background: #4CAF50;
            color: white;
        }

        .btn-primary:hover {
            background: #45a049;
        }

        .btn-update {
            background: #4CAF50;
            color: white;
            width: 100%;
            margin-top: 10px;
        }

        .btn-update:hover {
            background: #45a049;
        }

        .confirmation-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2000;
            align-items: center;
            justify-content: center;
        }

        .confirmation-modal.show {
            display: flex;
        }

        .confirmation-modal-content {
            background: white;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .confirmation-modal-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .confirmation-modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }

        .btn-no {
            background: #dc3545;
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-no:hover {
            background: #c82333;
        }

        .btn-yes {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-yes:hover {
            background: #45a049;
        }

        .success-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 2001;
            align-items: center;
            justify-content: center;
        }

        .success-modal.show {
            display: flex;
        }

        .success-modal-content {
            background: white;
            border-radius: 12px;
            padding: 40px;
            text-align: center;
            max-width: 400px;
            width: 90%;
        }

        .success-modal-title {
            font-size: 20px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .btn-ok {
            background: #2196F3;
            color: white;
            border: none;
            padding: 12px 32px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 16px;
            font-weight: 500;
        }

        .btn-ok:hover {
            background: #0b7dda;
        }

        .error-message {
            color: #D32F2F;
            font-size: 12px;
            margin-top: 5px;
        }

        .success-message {
            background: #E8F5E9;
            color: #2E7D32;
            padding: 12px 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>

    <div class="password-container">
        <h1 class="password-title">Change Password</h1>

        @if($errors->any())
            <div style="background: #ffebee; border: 2px solid #f44336; color: #c62828; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="password-card">
            <form method="POST" action="{{ route('password.update') }}" id="passwordForm">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="current_password">Current Password <span style="color: #dc3545;">*</span></label>
                    <div class="password-input-wrapper">
                        <input type="password" id="current_password" name="current_password" class="form-input" value="{{ old('current_password') }}" placeholder="Enter Current Password" required autofocus>
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('current_password', 'toggle_current')">
                            <span id="toggle_current">👁️</span>
                        </button>
                    </div>
                    @error('current_password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                    <div style="font-size: 12px; color: #666; margin-top: 5px;">
                        You must enter your current password to verify your identity.
                    </div>
                </div>

                <div class="form-group">
                    <label class="form-label" for="password">New Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password" name="password" class="form-input" placeholder="Enter New Password" required>
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password', 'toggle_new')">
                            <span id="toggle_new">👁️</span>
                        </button>
                    </div>
                    @error('password')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="password_confirmation">Confirm New Password</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-input" placeholder="Enter Confirm New Password" required>
                        <button type="button" class="password-toggle" onclick="togglePasswordVisibility('password_confirmation', 'toggle_confirm')">
                            <span id="toggle_confirm">👁️</span>
                        </button>
                    </div>
                </div>

                <button type="button" class="btn btn-update" onclick="showConfirmationModal()">Update Password</button>
            </form>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div class="confirmation-modal" id="confirmationModal">
        <div class="confirmation-modal-content">
            <div class="confirmation-modal-title">Are you sure you want to update your password?</div>
            <div class="confirmation-modal-buttons">
                <button type="button" class="btn-no" onclick="closeConfirmationModal()">No</button>
                <button type="button" class="btn-yes" onclick="submitPasswordForm()">Yes</button>
            </div>
        </div>
    </div>

    <!-- Success Modal -->
    @if(session('success'))
    <div class="success-modal show" id="successModal">
        <div class="success-modal-content">
            <div class="success-modal-title">{{ session('success') }}</div>
            <button type="button" class="btn-ok" onclick="closeSuccessModal()">OK</button>
        </div>
    </div>
    @endif

    <script>
        function togglePasswordVisibility(inputId, toggleId) {
            const input = document.getElementById(inputId);
            const toggle = document.getElementById(toggleId);

            if (input.type === 'password') {
                input.type = 'text';
                toggle.textContent = '🙈';
            } else {
                input.type = 'password';
                toggle.textContent = '👁️';
            }
        }

        function showConfirmationModal() {
            document.getElementById('confirmationModal').classList.add('show');
        }

        function closeConfirmationModal() {
            document.getElementById('confirmationModal').classList.remove('show');
        }

        function submitPasswordForm() {
            document.getElementById('passwordForm').submit();
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('show');
        }

        // Close modals when clicking outside
        document.addEventListener('click', function(event) {
            const confirmationModal = document.getElementById('confirmationModal');
            const successModal = document.getElementById('successModal');

            if (confirmationModal && event.target === confirmationModal) {
                closeConfirmationModal();
            }

            if (successModal && event.target === successModal) {
                closeSuccessModal();
            }
        });
    </script>
@endsection

