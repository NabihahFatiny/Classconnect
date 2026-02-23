<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'ClassConnect') - High School Learning Platform</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #F2EFDF;
            display: flex;
            flex-direction: column;
            height: 100vh;
            overflow: hidden;
        }

        /* Top Navigation Bar */
        .top-nav {
            background: white;
            height: 70px;
            display: flex;
            align-items: center;
            padding: 0 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            z-index: 1000;
        }

        .hamburger {
            width: 24px;
            height: 24px;
            cursor: pointer;
            margin-right: 20px;
            color: #333;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.2s;
        }

        .hamburger:hover {
            opacity: 0.7;
        }

        .hamburger svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
            stroke-width: 2;
        }

        .logo {
            font-family: 'Brush Script MT', cursive;
            font-size: 28px;
            font-weight: bold;
            color: #000000;
            flex: 1;
        }

        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .user-details {
            text-align: right;
        }

        .user-name {
            font-weight: 600;
            color: #333;
            font-size: 14px;
        }

        .user-role {
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
            cursor: pointer;
        }

        .user-info {
            cursor: pointer;
            transition: opacity 0.2s;
            position: relative;
        }

        .user-info:hover {
            opacity: 0.8;
        }

        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }

        .user-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            margin-top: 10px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            min-width: 150px;
            display: none;
            z-index: 1000;
        }

        .user-dropdown.show {
            display: block;
        }

        .user-dropdown-item {
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            display: block;
            transition: background 0.2s;
        }

        .user-dropdown-item:hover {
            background: #f0f0f0;
        }

        .user-dropdown-item.logout {
            color: #dc3545;
            border-top: 1px solid #eee;
        }

        .user-dropdown-item.logout:hover {
            background: #fee;
        }

        /* Main Container */
        .main-container {
            display: flex;
            flex: 1;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 280px;
            background: white;
            border-radius: 20px 20px 0 0;
            margin: 10px 0 10px 10px;
            padding: 20px 0;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            transition: all 0.3s ease;
            position: relative;
        }

        .sidebar.hidden {
            transform: translateX(-100%);
            opacity: 0;
            width: 0;
            margin-left: 0;
            padding: 0;
            overflow: hidden;
        }

        .nav-item {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
            cursor: pointer;
        }

        .nav-item:hover {
            background: #f0f0f0;
        }

        .nav-item.active {
            background: #795E2E;
            color: white;
        }

        .nav-item.active.discussion {
            background: #795E2E;
            color: white;
        }

        .nav-icon {
            width: 24px;
            height: 24px;
            margin-right: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .nav-icon svg {
            width: 20px;
            height: 20px;
            stroke: currentColor;
            fill: none;
        }

        .nav-item.active .nav-icon svg {
            stroke: white;
        }

        .nav-label {
            flex: 1;
        }

        .nav-arrow {
            width: 16px;
            height: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            transition: transform 0.3s;
        }

        .nav-arrow svg {
            width: 12px;
            height: 12px;
            stroke: currentColor;
            fill: none;
        }

        .nav-item.expanded .nav-arrow {
            transform: rotate(90deg);
        }

        .nav-item.active .nav-arrow {
            color: white;
        }

        .nav-submenu {
            padding-left: 56px;
            display: none;
        }

        .nav-item.expanded .nav-submenu {
            display: block;
        }

        .nav-submenu .nav-item {
            padding: 10px 20px;
            font-size: 14px;
        }

        .nav-submenu .nav-item.active {
            background: #795E2E;
            color: white;
        }

        .nav-item.expandable {
            position: relative;
        }

        .nav-item.expandable .nav-arrow {
            transition: transform 0.3s;
        }

        .nav-item.expandable.expanded .nav-arrow {
            transform: rotate(90deg);
        }

        /* Main Content Area */
        .content-area {
            flex: 1;
            background: #F2EFDF;
            margin: 10px 10px 10px 0;
            border-radius: 20px 20px 0 0;
            padding: 30px;
            overflow-y: auto;
        }

        /* Loading Screen */
        .loading-screen {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: #F2EFDF;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            z-index: 10000;
            transition: opacity 0.5s ease-out, visibility 0.5s ease-out;
        }

        .loading-screen.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loading-logo {
            font-family: 'Brush Script MT', cursive;
            font-size: 48px;
            font-weight: bold;
            color: #795E2E;
            margin-bottom: 30px;
            animation: fadeIn 0.8s ease-in;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid rgba(121, 94, 46, 0.1);
            border-top-color: #795E2E;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        .loading-text {
            margin-top: 20px;
            color: #795E2E;
            font-size: 16px;
            font-weight: 500;
            animation: fadeIn 1s ease-in;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
    </style>
</head>
<body>
    <!-- Loading Screen -->
    <div class="loading-screen" id="loadingScreen">
        <div class="loading-logo">ClassConnect</div>
        <div class="loading-spinner"></div>
        <div class="loading-text">Loading...</div>
    </div>
    <!-- Top Navigation Bar -->
    <div class="top-nav">
        <div class="hamburger">
            <svg viewBox="0 0 24 24">
                <line x1="3" y1="6" x2="21" y2="6"></line>
                <line x1="3" y1="12" x2="21" y2="12"></line>
                <line x1="3" y1="18" x2="21" y2="18"></line>
            </svg>
        </div>
        <div class="logo">ClassConnect</div>
        @auth
        <div class="user-info" onclick="toggleUserDropdown(event)" style="cursor: pointer;">
            <div class="user-details">
                <div class="user-name">{{ auth()->user()->name }}</div>
                <div class="user-role">
                    {{ ucfirst(auth()->user()->user_type) }}
                    <span>▼</span>
                </div>
            </div>
            <div class="user-avatar">
                @php
                    $name = auth()->user()->name;
                    $parts = explode(' ', $name);
                    $initials = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : substr($parts[0], 1, 1)));
                @endphp
                {{ $initials }}
            </div>
            <div class="user-dropdown" id="userDropdown">
                <a href="{{ route('profiles.index') }}" class="user-dropdown-item">Profile</a>
                <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                    @csrf
                    <button type="submit" class="user-dropdown-item logout" style="width: 100%; text-align: left; border: none; background: none; cursor: pointer; padding: 12px 20px;">
                        Logout
                    </button>
                </form>
            </div>
        </div>
        @endauth
    </div>

    <!-- Connection Status Alert -->
    <div id="offline-alert" class="alert alert-danger position-fixed top-0 start-50 translate-middle-x mt-2" style="display: none; z-index: 9999; min-width: 300px;">
        <i class="bi bi-wifi-off"></i> No internet connection. Please check your WiFi.
    </div>

    <!-- Main Container -->
    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="{{ route('dashboard') }}" class="nav-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                        <line x1="8" y1="21" x2="16" y2="21"></line>
                        <line x1="12" y1="17" x2="12" y2="21"></line>
                    </svg>
                </div>
                <div class="nav-label">Dashboard</div>
            </a>

            <div class="nav-item expandable {{ request()->is('profiles*') || request()->is('password*') ? 'expanded' : '' }}" onclick="toggleProfileMenu(event)">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <div class="nav-label">Profile</div>
                <div class="nav-arrow">
                    <svg viewBox="0 0 24 24">
                        <polyline points="9 18 15 12 9 6"></polyline>
                    </svg>
                </div>
            </div>
            <div class="nav-submenu {{ request()->is('profiles*') || request()->is('password*') ? '' : '' }}" style="{{ request()->is('profiles*') || request()->is('password*') ? 'display: block;' : '' }}">
                <a href="{{ route('profiles.index') }}" class="nav-item {{ request()->routeIs('profiles.index') ? 'active' : '' }}">
                    User Profile
                </a>
                <a href="{{ route('password.change') }}" class="nav-item {{ request()->routeIs('password.change') ? 'active' : '' }}">
                    Change Password
                </a>
            </div>

            <a href="{{ route('lessons.index') }}" class="nav-item {{ request()->routeIs('lessons*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path>
                        <path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path>
                        <line x1="8" y1="7" x2="18" y2="7"></line>
                        <line x1="8" y1="11" x2="18" y2="11"></line>
                        <line x1="8" y1="15" x2="14" y2="15"></line>
                    </svg>
                </div>
                <div class="nav-label">Lesson</div>
            </a>

            <a href="{{ route('assignments.index') }}" class="nav-item {{ request()->routeIs('assignments*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <div class="nav-label">Assignment</div>
            </a>

            <a href="{{ route('discussions.index') }}" class="nav-item {{ request()->routeIs('discussions*') || request()->routeIs('subjects*') ? 'active' : '' }}">
                <div class="nav-icon">
                    <svg viewBox="0 0 24 24">
                        <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"></path>
                    </svg>
                </div>
                <div class="nav-label">Discussion</div>
            </a>
        </div>

        <!-- Main Content Area -->
        <div class="content-area">
            @yield('content')
        </div>
    </div>

    <script>
        // Toggle sidebar visibility
        function toggleSidebar() {
            const sidebar = document.querySelector('.sidebar');
            if (sidebar) {
                sidebar.classList.toggle('hidden');
                // Save sidebar state to localStorage
                localStorage.setItem('sidebarHidden', sidebar.classList.contains('hidden'));
            }
        }

        // Loading screen functions
        function showLoadingScreen() {
            let loadingScreen = document.getElementById('loadingScreen');
            if (!loadingScreen) {
                loadingScreen = document.createElement('div');
                loadingScreen.id = 'loadingScreen';
                loadingScreen.className = 'loading-screen';
                loadingScreen.innerHTML = `
                    <div class="loading-logo">ClassConnect</div>
                    <div class="loading-spinner"></div>
                    <div class="loading-text">Loading...</div>
                `;
                document.body.appendChild(loadingScreen);
            }
            loadingScreen.classList.remove('hidden');
        }

        function hideLoadingScreen() {
            const loadingScreen = document.getElementById('loadingScreen');
            if (loadingScreen) {
                loadingScreen.classList.add('hidden');
                setTimeout(function() {
                    if (loadingScreen.parentNode) {
                        loadingScreen.remove();
                    }
                }, 500);
            }
        }

        // Check for internet connection
        const offlineAlert = document.getElementById('offline-alert');

        window.addEventListener('offline', function() {
            offlineAlert.style.display = 'block';
        });

        window.addEventListener('online', function() {
            offlineAlert.style.display = 'none';
        });

        // Check connection status on page load
        if (!navigator.onLine) {
            offlineAlert.style.display = 'block';
        }

        // Initialize sidebar state from localStorage
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarHidden = localStorage.getItem('sidebarHidden') === 'true';
            if (sidebar && sidebarHidden) {
                sidebar.classList.add('hidden');
            }

            // Hide loading screen when page is loaded
            window.addEventListener('load', function() {
                setTimeout(function() {
                    hideLoadingScreen();
                }, 300);
            });

            // Show loading screen on form submissions
            const forms = document.querySelectorAll('form[method="post"], form[method="POST"]');
            forms.forEach(function(form) {
                form.addEventListener('submit', function() {
                    showLoadingScreen();
                });
            });

            // Show loading screen on link clicks (navigation)
            const links = document.querySelectorAll('a[href]:not([href^="#"]):not([href^="javascript:"])');
            links.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    // Don't show loading for external links or links with target="_blank"
                    if (link.hostname !== window.location.hostname || link.target === '_blank') {
                        return;
                    }
                    showLoadingScreen();
                });
            });
        });

        // Add click event to hamburger menu
        document.addEventListener('DOMContentLoaded', function() {
            const hamburger = document.querySelector('.hamburger');
            if (hamburger) {
                hamburger.addEventListener('click', toggleSidebar);
            }
        });

        function toggleUserDropdown(event) {
            event.stopPropagation();
            const dropdown = document.getElementById('userDropdown');
            dropdown.classList.toggle('show');
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(event) {
            const userInfo = document.querySelector('.user-info');
            const dropdown = document.getElementById('userDropdown');
            if (userInfo && dropdown && !userInfo.contains(event.target)) {
                dropdown.classList.remove('show');
            }
        });

        function toggleProfileMenu(event) {
            event.preventDefault();
            const menuItem = event.currentTarget;
            menuItem.classList.toggle('expanded');
            const submenu = menuItem.nextElementSibling;
            if (submenu && submenu.classList.contains('nav-submenu')) {
                submenu.style.display = submenu.style.display === 'none' ? 'block' : 'none';
            }
        }

        // Session Management: Keep session alive during rapid navigation
        // This ensures sessions remain active for at least 30 minutes of activity
        @auth
        (function() {
            let lastActivityTime = Date.now();
            let sessionRefreshInterval = null;
            const SESSION_REFRESH_INTERVAL = 5 * 60 * 1000; // Refresh every 5 minutes
            const ACTIVITY_THRESHOLD = 30 * 1000; // Consider user active if activity within 30 seconds

            // Track user activity
            const activityEvents = ['mousedown', 'mousemove', 'keypress', 'scroll', 'touchstart', 'click'];
            activityEvents.forEach(event => {
                document.addEventListener(event, function() {
                    lastActivityTime = Date.now();
                }, { passive: true });
            });

            // Refresh session periodically if user is active
            function refreshSessionIfActive() {
                const timeSinceLastActivity = Date.now() - lastActivityTime;

                // Only refresh if user has been active recently (within 30 seconds)
                if (timeSinceLastActivity < ACTIVITY_THRESHOLD) {
                    // Make a lightweight request to refresh session
                    fetch('{{ route("session.keep-alive") }}', {
                        method: 'GET',
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                        }
                    })
                    .then(function(response) {
                        if (!response.ok) {
                            // Non-OK responses are expected (e.g., 401, 429) - don't log as errors
                            return { status: 'error', httpStatus: response.status };
                        }
                        return response.json().catch(function() {
                            // If response isn't JSON, that's okay
                            return { status: 'ok' };
                        });
                    })
                    .then(function(data) {
                        if (data && data.status === 'expired') {
                            // Stop the interval if session expired
                            if (sessionRefreshInterval) {
                                clearInterval(sessionRefreshInterval);
                                sessionRefreshInterval = null;
                            }
                        }
                    })
                    .catch(function(error) {
                        // Silently ignore all errors - session refresh is best effort
                        // Don't log anything to avoid console noise
                    });
                }
            }

            // Start periodic session refresh
            sessionRefreshInterval = setInterval(refreshSessionIfActive, SESSION_REFRESH_INTERVAL);

            // Refresh session on page navigation (beforeunload)
            window.addEventListener('beforeunload', function() {
                // Quick session touch before navigation - silently fail if it doesn't work
                try {
                    if (navigator.sendBeacon) {
                        navigator.sendBeacon('{{ route("session.keep-alive") }}');
                    }
                } catch (e) {
                    // Silently ignore errors
                }
            });

            // Refresh session on page visibility change (user switches tabs)
            document.addEventListener('visibilitychange', function() {
                if (!document.hidden && Date.now() - lastActivityTime < ACTIVITY_THRESHOLD) {
                    refreshSessionIfActive();
                }
            });

            // Cleanup on page unload
            window.addEventListener('unload', function() {
                if (sessionRefreshInterval) {
                    clearInterval(sessionRefreshInterval);
                }
            });
        })();
        @endauth
    </script>
    <!-- ===== Two-stage Delete Confirmation Modal (Global) ===== -->
<style>
  .cc-modal-backdrop{
    position: fixed; inset: 0;
    background: rgba(0,0,0,.45);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 9999;
    padding: 16px;
  }
  .cc-modal{
    width: 100%;
    max-width: 520px;
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 10px 30px rgba(0,0,0,.2);
    overflow: hidden;
  }
  .cc-modal-header{
    padding: 16px 18px;
    border-bottom: 1px solid #eee;
    display:flex;
    gap:10px;
    align-items:flex-start;
    justify-content:space-between;
  }
  .cc-modal-title{
    font-weight: 900;
    font-size: 18px;
    color: #222;
  }
  .cc-modal-close{
    border:none;
    background: transparent;
    font-size: 20px;
    cursor:pointer;
    color:#666;
  }
  .cc-modal-body{
    padding: 16px 18px;
    color:#333;
  }
  .cc-warn{
    background:#fff7f7;
    border:1px solid #f3c2c2;
    padding: 10px 12px;
    border-radius: 12px;
    color:#8a1f1f;
    font-size: 13px;
    margin-top: 10px;
  }
  .cc-modal-actions{
    padding: 14px 18px;
    border-top: 1px solid #eee;
    display:flex;
    gap: 10px;
    justify-content: flex-end;
    flex-wrap: wrap;
  }
  .cc-btn{
    padding: 10px 14px;
    border-radius: 10px;
    border: none;
    cursor: pointer;
    font-weight: 900;
    font-size: 14px;
  }
  .cc-btn-cancel{
    background:#f2f2f2;
    color:#222;
  }
  .cc-btn-primary{
    background:#795E2E;
    color:#fff;
  }
  .cc-btn-danger{
    background:#dc3545;
    color:#fff;
  }
  .cc-btn:disabled{
    opacity:.55;
    cursor:not-allowed;
  }
  .cc-check{
    display:flex;
    gap:10px;
    align-items:flex-start;
    font-size: 13px;
    color:#444;
    margin-top: 10px;
  }
</style>

<div class="cc-modal-backdrop" id="ccDeleteModal" aria-hidden="true">
  <div class="cc-modal" role="dialog" aria-modal="true" aria-labelledby="ccDeleteTitle">
    <div class="cc-modal-header">
      <div>
        <div class="cc-modal-title" id="ccDeleteTitle">Delete Assignment</div>
        <div style="font-size:12px; color:#777; margin-top:4px;" id="ccDeleteSub"></div>
      </div>
      <button class="cc-modal-close" type="button" onclick="ccCloseDeleteModal()">×</button>
    </div>

    <div class="cc-modal-body">
      <!-- Step 1 -->
      <div id="ccStep1">
        <div>
          Are you sure you want to delete <strong id="ccAssignmentName1"></strong>?
        </div>
        <div class="cc-warn">
          This action cannot be undone.
        </div>
      </div>

      <!-- Step 2 -->
      <div id="ccStep2" style="display:none;">
        <div style="font-weight:900; margin-bottom:6px;">Final confirmation</div>
        <div>
          You are about to permanently delete <strong id="ccAssignmentName2"></strong>.
        </div>

        <div class="cc-check">
          <input type="checkbox" id="ccAcknowledge" />
          <label for="ccAcknowledge">
            I understand this will permanently delete the assignment and cannot be undone.
          </label>
        </div>

        <div class="cc-warn">
          Tip: Click <strong>Cancel</strong> if you clicked delete by mistake.
        </div>
      </div>
    </div>

    <div class="cc-modal-actions">
      <!-- Prominent Cancel button (always available) -->
      <button class="cc-btn cc-btn-cancel" type="button" onclick="ccCloseDeleteModal()">Cancel</button>

      <!-- Step 1 buttons -->
      <button class="cc-btn cc-btn-primary" type="button" id="ccContinueBtn" onclick="ccGoStep2()">Continue</button>

      <!-- Step 2 button -->
      <button class="cc-btn cc-btn-danger" type="button" id="ccDeleteBtn" onclick="ccSubmitDelete()" style="display:none;" disabled>
        Delete
      </button>
    </div>
  </div>
</div>

<script>
  let ccDeleteFormId = null;
  let ccDeleteTitle = '';

  function ccOpenDeleteModal(assignmentTitle, formId){
    ccDeleteFormId = formId;
    ccDeleteTitle = assignmentTitle || 'this assignment';

    document.getElementById('ccAssignmentName1').textContent = ccDeleteTitle;
    document.getElementById('ccAssignmentName2').textContent = ccDeleteTitle;
    document.getElementById('ccDeleteSub').textContent = "Two-step confirmation required";

    // reset to step 1
    document.getElementById('ccStep1').style.display = 'block';
    document.getElementById('ccStep2').style.display = 'none';

    const ack = document.getElementById('ccAcknowledge');
    ack.checked = false;

    document.getElementById('ccContinueBtn').style.display = 'inline-block';
    const delBtn = document.getElementById('ccDeleteBtn');
    delBtn.style.display = 'none';
    delBtn.disabled = true;

    // show modal
    const modal = document.getElementById('ccDeleteModal');
    modal.style.display = 'flex';
    modal.setAttribute('aria-hidden', 'false');

    // close on ESC
    document.addEventListener('keydown', ccEscCloseOnce);

    // enable delete only when acknowledged
    ack.onchange = function(){
      delBtn.disabled = !ack.checked;
    };
  }

  function ccGoStep2(){
    document.getElementById('ccStep1').style.display = 'none';
    document.getElementById('ccStep2').style.display = 'block';

    document.getElementById('ccContinueBtn').style.display = 'none';
    const delBtn = document.getElementById('ccDeleteBtn');
    delBtn.style.display = 'inline-block';
    delBtn.disabled = !document.getElementById('ccAcknowledge').checked;
  }

  function ccCloseDeleteModal(){
    const modal = document.getElementById('ccDeleteModal');
    modal.style.display = 'none';
    modal.setAttribute('aria-hidden', 'true');
    ccDeleteFormId = null;
    document.removeEventListener('keydown', ccEscCloseOnce);
  }

  function ccEscCloseOnce(e){
    if (e.key === 'Escape') ccCloseDeleteModal();
  }

  function ccSubmitDelete(){
    if (!ccDeleteFormId) return;

    const form = document.getElementById(ccDeleteFormId);
    if (!form) return;

    form.submit();
  }

  // close if click outside dialog
  document.addEventListener('click', function(e){
    const modal = document.getElementById('ccDeleteModal');
    if (!modal || modal.style.display !== 'flex') return;
    if (e.target === modal) ccCloseDeleteModal();
  });
</script>

</body>
</html>

