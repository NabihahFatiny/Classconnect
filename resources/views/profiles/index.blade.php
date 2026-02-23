@extends('layouts.app')

@section('title', 'User Profile')

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

        .profile-content {
            display: flex;
            gap: 30px;
            flex-wrap: wrap;
        }

        .profile-card {
            background: white;
            border-radius: 12px;
            padding: 30px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-picture-card {
            flex: 0 0 300px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .profile-info-card {
            flex: 1;
            min-width: 400px;
        }

        .profile-picture {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            background: #E0E0E0;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 20px;
            position: relative;
            overflow: hidden;
        }

        .profile-picture-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #D0D0D0;
            color: #999;
            font-size: 80px;
        }

        .change-photo-btn {
            background: #E8F5E9;
            color: #4CAF50;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.2s;
        }

        .change-photo-btn:hover {
            background: #C8E6C9;
        }

        .profile-picture img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-modal {
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

        .photo-modal.show {
            display: flex;
        }

        .photo-modal-content {
            background: white;
            border-radius: 12px;
            padding: 30px;
            max-width: 500px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }

        .photo-modal-title {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            margin-bottom: 20px;
        }

        .photo-preview {
            width: 200px;
            height: 200px;
            border-radius: 50%;
            margin: 20px auto;
            overflow: hidden;
            background: #E0E0E0;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .photo-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .photo-upload-input {
            width: 100%;
            padding: 12px;
            border: 2px solid #ddd;
            border-radius: 8px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .photo-modal-buttons {
            display: flex;
            gap: 10px;
            justify-content: flex-end;
        }

        .btn-save {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-save:hover {
            background: #45a049;
        }

        .btn-delete {
            background: #dc3545;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-delete:hover {
            background: #c82333;
        }

        .btn-cancel {
            background: #6c757d;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-cancel:hover {
            background: #5a6268;
        }

        .success-message {
            background: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .profile-info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .profile-info-table tr {
            border-bottom: 1px solid #F0F0F0;
        }

        .profile-info-table tr:last-child {
            border-bottom: none;
        }

        .profile-info-table td {
            padding: 15px 0;
            vertical-align: top;
        }

        .profile-info-label {
            font-weight: 600;
            color: #666;
            width: 40%;
            padding-right: 20px;
        }

        .profile-info-value {
            color: #333;
        }

        .edit-profile-btn {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            float: right;
            margin-top: 20px;
            transition: background 0.2s;
        }

        .edit-profile-btn:hover {
            background: #45a049;
        }

        @media (max-width: 768px) {
            .profile-content {
                flex-direction: column;
            }

            .profile-picture-card {
                flex: 1;
            }

            .profile-info-card {
                min-width: 100%;
            }
        }
    </style>

    <div class="profile-container">
        <h1 class="profile-title">Profile</h1>

        @if(session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
        @endif

        <div class="profile-content">
            <!-- Profile Picture Card -->
            <div class="profile-card profile-picture-card">
                <div class="profile-picture">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Profile Photo">
                    @else
                        <div class="profile-picture-placeholder">👤</div>
                    @endif
                </div>
                <button class="change-photo-btn" onclick="handleChangePhoto()">Change Photo</button>
            </div>

            <!-- Profile Information Card -->
            <div class="profile-card profile-info-card">
                <table class="profile-info-table">
                    <tr>
                        <td class="profile-info-label">Name</td>
                        <td class="profile-info-value">{{ $user->name }}</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">Username</td>
                        <td class="profile-info-value">{{ $user->username }}</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">Email</td>
                        <td class="profile-info-value">{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">Phone Number</td>
                        <td class="profile-info-value">{{ $user->mobile_phone ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">Date of birth</td>
                        <td class="profile-info-value">{{ $user->date_of_birth ? $user->date_of_birth->format('d/m/Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">User ID</td>
                        <td class="profile-info-value">{{ $user->user_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td class="profile-info-label">User Type</td>
                        <td class="profile-info-value">{{ ucfirst($user->user_type) }}</td>
                    </tr>
                </table>
                <a href="{{ route('profiles.edit') }}" class="edit-profile-btn" style="text-decoration: none; display: inline-block;">Edit Profile</a>
            </div>
        </div>
    </div>

    <!-- Photo Upload Modal -->
    <div class="photo-modal" id="photoModal">
        <div class="photo-modal-content">
            <h2 class="photo-modal-title">Change Profile Photo</h2>
            <form id="photoForm" method="POST" action="{{ route('profiles.update-photo') }}" enctype="multipart/form-data">
                @csrf
                <div class="photo-preview" id="photoPreview">
                    @if($user->photo)
                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Current Photo" id="previewImage">
                    @else
                        <div class="profile-picture-placeholder">👤</div>
                    @endif
                </div>
                <input type="file" name="photo" id="photoInput" class="photo-upload-input" accept="image/*" required>
                @error('photo')
                    <div style="color: #dc3545; font-size: 14px; margin-bottom: 10px;">{{ $message }}</div>
                @enderror
                <div class="photo-modal-buttons">
                    @if($user->photo)
                        <button type="button" class="btn-delete" onclick="deletePhoto()">Delete Current Photo</button>
                    @endif
                    <button type="button" class="btn-cancel" onclick="closePhotoModal()">Cancel</button>
                    <button type="submit" class="btn-save">Save Photo</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function handleChangePhoto() {
            document.getElementById('photoModal').classList.add('show');
        }

        function closePhotoModal() {
            document.getElementById('photoModal').classList.remove('show');
            document.getElementById('photoInput').value = '';
            resetPreview();
        }

        function resetPreview() {
            const preview = document.getElementById('photoPreview');
            const currentPhoto = @json($user->photo ? asset('storage/' . $user->photo) : null);

            if (currentPhoto) {
                preview.innerHTML = '<img src="' + currentPhoto + '" alt="Current Photo" id="previewImage">';
            } else {
                preview.innerHTML = '<div class="profile-picture-placeholder">👤</div>';
            }
        }

        document.getElementById('photoInput').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById('photoPreview');
                    preview.innerHTML = '<img src="' + e.target.result + '" alt="Preview" id="previewImage">';
                };
                reader.readAsDataURL(file);
            }
        });

        function deletePhoto() {
            if (confirm('Are you sure you want to delete your profile photo?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("profiles.delete-photo") }}';

                const csrf = document.createElement('input');
                csrf.type = 'hidden';
                csrf.name = '_token';
                csrf.value = '{{ csrf_token() }}';
                form.appendChild(csrf);

                document.body.appendChild(form);
                form.submit();
            }
        }

        // Close modal when clicking outside
        document.getElementById('photoModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closePhotoModal();
            }
        });

        function handleEditProfile() {
            // Handle edit profile functionality
            console.log('Edit profile clicked');
            // TODO: Navigate to edit profile page
        }
    </script>
@endsection
