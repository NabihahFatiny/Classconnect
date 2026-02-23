# Quick Fix: Database Connection Settings

Based on your Render database, here are the exact values you need:

## Database Connection Information (from your Render dashboard):

- **Hostname**: `dpg-d58etaruibrs73am7itg-a`
- **Port**: `5432`
- **Database**: `classconnect_4vse`
- **Username**: `classconnect_user`
- **Password**: `s10Hqb022hC2hQ83yNmklyxLVMJ1Ps6H`

## Steps to Fix:

### Option 1: Set in Render Dashboard (Easiest)

1. Go to your **web service** (classconnect) in Render Dashboard
2. Click on **"Environment"** tab
3. Add/Update these environment variables:

```
DB_CONNECTION=pgsql
DB_HOST=dpg-d58etaruibrs73am7itg-a
DB_PORT=5432
DB_DATABASE=classconnect_4vse
DB_USERNAME=classconnect_user
DB_PASSWORD=s10Hqb022hC2hQ83yNmklyxLVMJ1Ps6H
```

4. **Save** the changes
5. The service will automatically redeploy

### Option 2: Update render.yaml (If you want to redeploy)

If you want the render.yaml to match your database, update it with the actual values, but note that Render should auto-fill these if the database name matches.

## After Setting Environment Variables:

1. Go to your web service **Shell** tab
2. Run these commands:

```bash
php artisan config:clear
php artisan cache:clear
php artisan migrate --force
```

3. Try creating an account again - it should work now!

## Important Note:

Your database is in **Oregon (US West)** region. Make sure your web service is also in the same region for best performance. If it's in Singapore, consider moving it to Oregon, or create a new database in Singapore.

