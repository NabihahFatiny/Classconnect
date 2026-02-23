@extends('layouts.app')

@section('title', 'Edit Profile')

@section('content')
    <style>
        .profile-container {
            max-width: 1200px;
        }

        .profile-title {
            font-size: 32px;
            font-weight: bold;
            color: #333;
            margin-bottom: 30px;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: #666;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: #f9f9f9;
            color: #333;
            transition: background 0.2s, border-color 0.2s;
        }

        .form-input:focus {
            outline: none;
            background: white;
            border-color: #9A7A4A;
        }

        .form-input:read-only {
            background: #e9ecef;
            cursor: not-allowed;
        }

        .date-input-group {
            display: flex;
            gap: 10px;
        }

        .date-select {
            flex: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 8px;
            font-size: 16px;
            background: #f9f9f9;
            color: #333;
            cursor: pointer;
        }

        .date-select:focus {
            outline: none;
            background: white;
            border-color: #9A7A4A;
        }

        .form-buttons {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 30px;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            text-decoration: none;
            display: inline-block;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .btn-save {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-save:hover {
            background: #45a049;
        }

        .error-message {
            color: #dc3545;
            font-size: 14px;
            margin-top: 5px;
        }

        .success-modal {
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
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
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
            margin-top: 20px;
        }

        .btn-ok:hover {
            background: #0b7dda;
        }
    </style>

    <div class="profile-container">
        <h1 class="profile-title">Edit Profile</h1>

        @if($errors->any())
            <div style="background: #fff3cd; border: 2px solid #ffc107; color: #856404; padding: 16px; border-radius: 8px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="profile-card">
            <form method="POST" action="{{ route('profiles.update') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label" for="name">Name</label>
                    <input type="text" id="name" name="name" class="form-input" value="{{ old('name', $user->name) }}" required>
                    @error('name')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="username">Username</label>
                    <input type="text" id="username" name="username" class="form-input" value="{{ old('username', $user->username) }}" required>
                    @error('username')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="email">Email</label>
                    <input type="email" id="email" name="email" class="form-input" value="{{ old('email', $user->email) }}" required>
                    @error('email')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="mobile_phone">Phone Number</label>
                    <input type="text" id="mobile_phone" name="mobile_phone" class="form-input" value="{{ old('mobile_phone', $user->mobile_phone) }}" required>
                    @error('mobile_phone')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="date_of_birth">Date of Birth</label>
                    <input type="date" id="date_of_birth" name="date_of_birth" class="form-input" value="{{ old('date_of_birth', $user->date_of_birth ? $user->date_of_birth->format('Y-m-d') : '') }}" required>
                    @error('date_of_birth')
                        <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label class="form-label" for="user_id">User ID</label>
                    <input type="text" id="user_id" name="user_id" class="form-input" value="{{ old('user_id', $user->user_id) }}" readonly>
                    <small style="color: #999; font-size: 12px; display: block; margin-top: 5px;">User ID cannot be changed</small>
                </div>

                <div class="form-buttons">
                    <a href="{{ route('profiles.index') }}" class="btn-cancel">Cancel</a>
                    <button type="submit" class="btn-save">Save</button>
                </div>
            </form>
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
        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('show');
            // Redirect to profile page after closing modal
            setTimeout(function() {
                window.location.href = '{{ route("profiles.index") }}';
            }, 300);
        }

        // Close modal when clicking outside
        document.addEventListener('click', function(event) {
            const modal = document.getElementById('successModal');
            if (modal && event.target === modal) {
                closeSuccessModal();
            }
        });
    </script>
@endsection


