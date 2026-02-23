# ClassConnect – Database setup (after cloning from GitHub)

The project does **not** include a `.sql` dump. The database is created and updated using **Laravel migrations**.

---

## Quick fix (recommended)

**Double-click `Fix Database.bat`** in the project folder. It will:

1. Create the database `classconnect` (if XAMPP MySQL is running).
2. Run all migrations so every table and column exists.

Then open ClassConnect and use the app.

---

## Manual steps

### 1. Create the database in MySQL

Using **XAMPP**:

1. Start **XAMPP** and start **Apache** and **MySQL**.
2. Open **http://localhost/phpmyadmin** in your browser.
3. Click **New** (or “Databases”) and create a database named: **`classconnect`**
4. Leave collation as default (e.g. `utf8mb4_general_ci`) and click **Create**.

---

## 2. Configure `.env`

In the project folder, the `.env` file should have:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=classconnect
DB_USERNAME=root
DB_PASSWORD=
```

- If your MySQL user has a password, set `DB_PASSWORD=your_password`.
- If you used a different database name, set `DB_DATABASE=your_database_name`.

---

## 3. Run migrations (create tables)

Open **Command Prompt** or **PowerShell** in the project folder and run:

```bat
cd C:\xampp\htdocs\ClassConnect
php artisan migrate
```

When asked “Do you want to run the migrations?”, type **yes** and press Enter.

This creates all tables (users, subjects, discussions, assignments, etc.) in the `classconnect` database.

---

## 4. (Optional) Seed initial data

To insert default data (e.g. sample subjects):

```bat
php artisan db:seed
```

---

## 5. If you *do* have a `.sql` file later

If someone gives you a **backup `.sql` file** (e.g. `classconnect.sql`):

1. Create the database **`classconnect`** in phpMyAdmin (as in step 1).
2. In phpMyAdmin, select the **classconnect** database.
3. Open the **Import** tab.
4. Choose the `.sql` file and click **Go**.

For this GitHub repo, **steps 1–3 (and optionally 4) are enough**; no `.sql` import is required.
