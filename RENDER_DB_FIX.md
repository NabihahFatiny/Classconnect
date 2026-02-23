# Quick Fix: Database Connection Settings

Based on your Render database connection string, here are the exact values you need to set in your Render web service:

## Database Connection Information:

- **Hostname**: `dpg-d58pab3uibrs73asnma0-a.oregon-postgres.render.com` (add `.oregon-postgres.render.com` to the short hostname)
- **Port**: `5432`
- **Database**: `classconnect_hhy8`
- **Username**: `classconnect_user`
- **Password**: `5esNi1gp6qPWh14CGqjLd9JnIh6lWGNN`

## Steps to Fix in Render Dashboard:

1. Go to your **web service** (`classconnect`) in Render Dashboard
2. Click on **"Environment"** tab
3. Update these environment variables:

```
DB_HOST=dpg-d58pab3uibrs73asnma0-a.oregon-postgres.render.com
DB_PORT=5432
DB_DATABASE=classconnect_hhy8
DB_USERNAME=classconnect_user
DB_PASSWORD=5esNi1gp6qPWh14CGqjLd9JnIh6lWGNN
DB_CONNECTION=pgsql
```

4. **Save** the changes
5. The service will automatically redeploy

## Alternative: Use DB_URL (Simpler)

Instead of individual variables, you can use a single `DB_URL`:

```
DB_URL=postgresql://classconnect_user:5esNi1gp6qPWh14CGqjLd9JnIh6lWGNN@dpg-d58pab3uibrs73asnma0-a.oregon-postgres.render.com:5432/classconnect_hhy8
```

Make sure to remove the individual `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` variables if you use `DB_URL`.

## After Setting Environment Variables:

1. Wait for the service to redeploy
2. Go to your web service **Shell** tab
3. Run these commands:

```bash
php artisan config:clear
php artisan cache:clear
php artisan migrate --force
```

4. Your application should now connect to the database successfully!

