# ClassConnect – Demo login (lecturer & student)

There are **no default passwords** in the GitHub repo. You either **register** a new account or use the **demo accounts** after seeding.

---

## Option 1: Demo accounts (after running the seeder)

Run the seeder to create one lecturer and one student with the same password:

```bat
cd C:\xampp\htdocs\ClassConnect
php artisan db:seed --class=DemoUserSeeder
```

Then log in with:

| Role     | Username   | Password  | User type (select in login) |
|----------|------------|-----------|-----------------------------|
| Student  | `student`  | `password`| Student                     |
| Lecturer | `lecturer` | `password`| Lecturer                    |

---

## Option 2: Register your own account

1. Open **http://localhost:8000**
2. Click **Create an account**
3. Fill in the form and choose **Student** or **Lecturer**
4. Use that username and password to log in

---

## Run all seeders (including demo users)

```bat
php artisan db:seed
```

This runs `DemoUserSeeder` (demo lecturer/student), `SubjectSeeder`, and the rest. You can then log in with **student** / **password** or **lecturer** / **password**.
