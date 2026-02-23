# Render Deployment Checklist

Follow these steps to deploy your ClassConnect application on Render.

## ✅ Pre-Deployment Checklist

### 1. **Ensure Your Code is on GitHub**
   - [ ] Your code is committed and pushed to a GitHub repository
   - [ ] You have access to the repository
   - [ ] The repository is either public or you've connected your GitHub account to Render

### 2. **Prepare Environment Variables**
   You'll need these values ready:
   - [ ] `APP_KEY` - Generate using: `php artisan key:generate --show` (run locally)
   - [ ] `APP_URL` - Will be provided by Render (e.g., `https://classconnect.onrender.com`)

## 🚀 Deployment Steps

### Step 1: Create Render Account
1. Go to [https://render.com](https://render.com)
2. Sign up or log in (you can use GitHub to sign in)

### Step 2: Deploy Using Blueprint (Recommended)
1. In Render Dashboard, click **"New +"** → **"Blueprint"**
2. Connect your GitHub account if you haven't already
3. Select your **ClassConnect repository**
4. Render will automatically detect `render.yaml` and show you what will be created:
   - Web Service: `classconnect`
   - PostgreSQL Database: `classconnect-db`
5. Click **"Apply"** to create the services

### Step 3: Set Required Environment Variables
After the services are created, you need to set these in the Web Service:

1. Go to your **Web Service** (classconnect)
2. Click on **"Environment"** tab
3. Add/Update these variables:

   **Required:**
   - `APP_KEY` = `base64:YOUR_GENERATED_KEY_HERE` (from Step 1)
   - `APP_URL` = `https://your-service-name.onrender.com` (Render will show this URL)

   **Note:** The database variables (`DB_HOST`, `DB_PORT`, etc.) are automatically set by Render from the `render.yaml` configuration.

### Step 4: Run Database Migrations
1. Go to your **Web Service** → **"Shell"** tab
2. Run the migration command:
   ```bash
   php artisan migrate --force
   ```
3. If you have seeders, you can run them too:
   ```bash
   php artisan db:seed --force
   ```

### Step 5: Verify Deployment
1. Visit your application URL (shown in Render dashboard)
2. Check if the application loads correctly
3. Test key functionality (login, registration, etc.)

## 🔧 Post-Deployment Configuration

### Storage Setup (Important!)
Render's filesystem is **ephemeral** - files are deleted when the service restarts. For file uploads:

**Option 1: Use Cloud Storage (Recommended)**
- Set up AWS S3, DigitalOcean Spaces, or similar
- Update your `config/filesystems.php` to use cloud storage
- Set environment variables for cloud storage credentials

**Option 2: Use Database Storage**
- Store file metadata in database
- Files themselves should still use cloud storage for large files

### Performance Optimization
- [ ] Clear caches after deployment: `php artisan config:clear && php artisan cache:clear`
- [ ] Consider upgrading from free tier for production (free tier spins down after 15 min inactivity)

## 🐛 Troubleshooting

### Service Won't Start
- Check **Logs** tab in Render dashboard
- Verify `APP_KEY` is set correctly
- Ensure all environment variables are set

### Database Connection Errors
- Verify database service is running
- Check database credentials in Environment tab
- Ensure `DB_CONNECTION=pgsql` (not `mysql` or `sqlite`)

### 500 Errors
- Check logs for specific error messages
- Verify migrations ran: `php artisan migrate:status`
- Clear caches: `php artisan config:clear && php artisan cache:clear`

### First Request is Slow
- This is normal on free tier - service spins down after 15 min inactivity
- First request after spin-down takes ~30 seconds
- Consider paid plan for production

## 📝 Quick Commands Reference

```bash
# Check migration status
php artisan migrate:status

# Run migrations
php artisan migrate --force

# Clear all caches
php artisan config:clear && php artisan cache:clear && php artisan route:clear && php artisan view:clear

# Generate new app key (if needed)
php artisan key:generate --show
```

## 🔗 Useful Links
- Render Dashboard: https://dashboard.render.com
- Render Docs: https://render.com/docs
- Your Service Logs: Available in Render Dashboard → Your Service → Logs

