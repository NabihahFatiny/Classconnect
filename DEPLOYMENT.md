# Deployment Guide for ClassConnect on Render

## Prerequisites
- A GitHub/GitLab/Bitbucket repository with your code
- A Render account (sign up at https://render.com)

## Step-by-Step Deployment Instructions

### Option 1: Using render.yaml (Recommended)

1. **Push your code to GitHub**
   ```bash
   git add .
   git commit -m "Add Render deployment configuration"
   git push origin main
   ```

2. **Connect Repository to Render**
   - Go to https://dashboard.render.com
   - Click "New +" → "Blueprint"
   - Connect your GitHub repository
   - Select the repository containing your ClassConnect project
   - Render will automatically detect `render.yaml` and create the services

3. **Set Environment Variables**
   After the services are created, you'll need to set these environment variables in the Render dashboard:
   
   - `APP_KEY`: Run `php artisan key:generate --show` locally and copy the key
   - `APP_URL`: Your Render service URL (will be something like `https://classconnect.onrender.com`)
   
   Optional variables you may want to configure:
   - `SESSION_DRIVER`: Set to `database` or `cookie` (default: `file` won't work on Render)
   - `CACHE_DRIVER`: Set to `database` or `redis` (default: `file` won't work on Render)
   - `QUEUE_CONNECTION`: Set to `database` or `sync` (default: `sync`)

4. **Run Migrations**
   - After the first deployment, go to your service's Shell tab
   - Run: `php artisan migrate --force`

5. **Set Up Storage**
   - Laravel's default `local` storage won't work on Render (ephemeral filesystem)
   - Consider using S3 or other cloud storage for file uploads
   - Or use the database to store file metadata

### Option 2: Manual Setup

If you prefer to set up manually:

1. **Create a PostgreSQL Database**
   - Go to Render Dashboard → "New +" → "PostgreSQL"
   - Name: `classconnect-db`
   - Plan: Free
   - Region: Singapore (or your preferred region)

2. **Create a Web Service**
   - Go to Render Dashboard → "New +" → "Web Service"
   - Connect your GitHub repository
   - Configure as follows:
     - **Name**: `classconnect`
     - **Region**: Singapore (or same as database)
     - **Branch**: `main`
     - **Root Directory**: (leave empty)
     - **Runtime**: `PHP`
     - **Build Command**: 
       ```
       composer install --no-dev --optimize-autoloader && php artisan config:cache && php artisan route:cache && php artisan view:cache && npm ci && npm run build
       ```
     - **Start Command**: 
       ```
       php artisan serve --host=0.0.0.0 --port=$PORT
       ```

3. **Set Environment Variables**
   Add these in the Environment section:
   - `APP_ENV=production`
   - `APP_DEBUG=false`
   - `APP_KEY=<generate-using-php-artisan-key-generate>`
   - `APP_URL=https://your-app-name.onrender.com`
   - `DB_CONNECTION=pgsql`
   - `DB_HOST=<from-database-info>`
   - `DB_PORT=<from-database-info>`
   - `DB_DATABASE=<from-database-info>`
   - `DB_USERNAME=<from-database-info>`
   - `DB_PASSWORD=<from-database-info>`
   - `LOG_CHANNEL=stderr`
   - `LOG_LEVEL=error`
   - `SESSION_DRIVER=database`
   - `CACHE_DRIVER=database`

4. **Run Migrations**
   - After first deploy, use the Shell tab in your service
   - Run: `php artisan migrate --force`

## Important Notes

### Storage Considerations
- Render's filesystem is **ephemeral** - files are deleted when the service restarts
- For file uploads (lessons, assignments, etc.), you should:
  1. Use cloud storage (AWS S3, DigitalOcean Spaces, etc.)
  2. Store files in the database (as BLOB, but not recommended for large files)
  3. Use a separate persistent volume service

### Database Configuration
- The default setup uses PostgreSQL (free tier available)
- Make sure your `database.php` config supports PostgreSQL
- You may need to update migrations if they're MySQL-specific

### Performance
- Free tier services spin down after 15 minutes of inactivity
- First request after spin-down takes longer (~30 seconds)
- Consider upgrading to a paid plan for production use

### SSL/HTTPS
- Render provides free SSL certificates automatically
- Your `APP_URL` should use `https://` protocol

## Troubleshooting

### Service won't start
- Check the logs in Render dashboard
- Verify all environment variables are set correctly
- Ensure `APP_KEY` is generated and set

### Database connection errors
- Verify database credentials are correct
- Check that the database service is running
- Ensure `DB_CONNECTION=pgsql` (not `mysql`)

### 500 errors after deployment
- Check logs for specific error messages
- Verify all migrations have run: `php artisan migrate:status`
- Clear cache: `php artisan config:clear && php artisan cache:clear`

### File uploads not working
- This is expected with default Laravel storage on Render
- Implement cloud storage solution (S3, etc.) for file persistence

