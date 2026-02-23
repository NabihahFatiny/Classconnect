# Why ClassConnect won't open in the browser – and how to fix it

Use **one** of these ways to open ClassConnect. If one fails, try the next.

---

## Method 1: Double-click **Open ClassConnect.bat** (recommended)

1. **Double-click** `Open ClassConnect.bat` in the project folder.
2. A **black window** titled **"ClassConnect Server - KEEP THIS OPEN"** will appear. **Do not close it.**
3. After a few seconds, your browser should open to **http://localhost:8000**.

**If the browser stays blank or "Can't connect":**

- Look at the **black server window**. If you see red error text, the server did not start.
- **Common causes:**
  - **Port 8000 in use** – Close other programs that might use port 8000, or run in Command Prompt:  
    `cd C:\xampp\htdocs\ClassConnect` then `php artisan serve --port=8001`  
    Then in the browser open: **http://localhost:8001**
  - **PHP not found** – Install XAMPP so that `C:\xampp\php\php.exe` exists, or add PHP to your system PATH.
  - **Composer missing** – If the bat file says "composer install failed", install Composer from https://getcomposer.org

---

## Method 2: Use XAMPP Apache (no PHP server needed)

1. Open **XAMPP Control Panel** and start **Apache** (and **MySQL** if you use the app).
2. **Double-click** `Open ClassConnect (XAMPP).bat` in the project folder.  
   Or manually open in the browser: **http://localhost/ClassConnect/public/**
3. The ClassConnect login page should load.

**If you get 404 or "Not Found":**

- Confirm the project is in `C:\xampp\htdocs\ClassConnect`.
- The URL must end with **/public/** – e.g. **http://localhost/ClassConnect/public/**

---

## Method 3: Start the server yourself, then open the browser

1. Open **Command Prompt** or **PowerShell**.
2. Run:
   ```bat
   cd C:\xampp\htdocs\ClassConnect
   php artisan serve
   ```
3. Leave that window open. When you see something like "Server running on http://127.0.0.1:8000", open your browser and go to: **http://localhost:8000**

---

## Checklist

| Check | Action |
|-------|--------|
| Server window open? | The "ClassConnect Server" or the terminal where you ran `php artisan serve` must stay open. |
| Correct URL? | Use **http://localhost:8000** (PHP server) or **http://localhost/ClassConnect/public/** (XAMPP). |
| Apache (for XAMPP)? | If using Method 2, Apache must be started in XAMPP. |
| Firewall / antivirus? | Allow PHP or your browser for "localhost" if needed. |

---

## Summary

- **Method 1:** Run `Open ClassConnect.bat` → keep the server window open → browser opens **http://localhost:8000**.
- **Method 2:** Start Apache in XAMPP → run `Open ClassConnect (XAMPP).bat` or open **http://localhost/ClassConnect/public/**.
- **Method 3:** Run `php artisan serve` in a terminal → open **http://localhost:8000** in the browser.

If it still doesn’t open, copy any **error message** from the black server window or the browser and use that to troubleshoot further.
