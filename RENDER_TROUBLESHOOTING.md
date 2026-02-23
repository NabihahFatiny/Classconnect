# Render Deployment Troubleshooting

## Database Connection Error: "Connection refused (Connection: mysql)"

### Problem
You're seeing errors like:
```
SQLSTATE[HY000] [2002] Connection refused (Connection: mysql, SQL: ...)
```

This means the app is trying to connect to MySQL, but Render uses PostgreSQL.

### Solution Steps

#### Step 1: Verify Database Service is Created
1. Go to Render Dashboard
2. Check if `classconnect-db` (PostgreSQL database) exists
3. If it doesn't exist, the `render.yaml` should create it automatically, but you can also create it manually:
   - Click "New +" → "PostgreSQL"
   - Name: `classconnect-db`
   - Plan: Free
   - Region: Singapore (same as your web service)

#### Step 2: Check Environment Variables in Render Dashboard
1. Go to your web service in Render Dashboard
2. Click on "Environment" tab
3. Verify these variables are set:
   - `DB_CONNECTION` = `pgsql` (NOT mysql!)
   - `DB_HOST` = (should be auto-filled from database)
   - `DB_PORT` = (should be auto-filled from database)
   - `DB_DATABASE` = (should be auto-filled from database)
   - `DB_USERNAME` = (should be auto-filled from database)
   - `DB_PASSWORD` = (should be auto-filled from database)

#### Step 3: Clear Config Cache
If you've deployed before with wrong settings, the config might be cached. In the Render Shell:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

#### Step 4: Run Migrations
After fixing the environment variables, run migrations:

```bash
php artisan migrate --force
```

#### Step 5: Verify Database Connection
Test the connection in Render Shell:

```bash
php artisan tinker
```

Then in tinker:
```php
DB::connection()->getPdo();
```

If it works, you'll see the PDO object. If not, you'll see the error.

### Manual Environment Variable Setup

If the `render.yaml` didn't set the variables automatically, add them manually in Render Dashboard:

1. Go to your web service → Environment
2. Add these variables:

```
DB_CONNECTION=pgsql
DB_HOST=<from database connection info>
DB_PORT=<from database connection info>
DB_DATABASE=<from database connection info>
DB_USERNAME=<from database connection info>
DB_PASSWORD=<from database connection info>
```

You can find the database connection info in:
- Render Dashboard → Your Database → "Connections" tab

### Common Issues

**Issue**: Environment variables show in dashboard but app still uses MySQL
**Fix**: Clear config cache (Step 3 above)

**Issue**: Database service doesn't exist
**Fix**: Create it manually or redeploy the blueprint

**Issue**: "Connection refused" even with correct variables
**Fix**: 
1. Check database is running (green status in Render)
2. Verify database and web service are in the same region
3. Check database internal hostname (should be something like `dpg-xxxxx-a`)

### Quick Fix Script

Run this in Render Shell to verify everything:

```bash
# Check environment variables
echo "DB_CONNECTION: $DB_CONNECTION"
echo "DB_HOST: $DB_HOST"
echo "DB_DATABASE: $DB_DATABASE"

# Clear all caches
php artisan config:clear
php artisan cache:clear

# Test database connection
php artisan migrate:status
```

If `migrate:status` works, your database connection is correct!

