# Google Sign-In Setup Guide

## 📋 Overview
This guide will help you set up Google OAuth 2.0 authentication for your marriage site.

## 🚀 Step-by-Step Setup

### Step 1: Create Google Cloud Project

1. **Go to Google Cloud Console**
   - Visit: https://console.cloud.google.com/
   - Sign in with your Google account

2. **Create a New Project**
   - Click "Select a project" at the top
   - Click "NEW PROJECT"
   - Enter project name: `Marriage Site` (or any name you prefer)
   - Click "CREATE"

### Step 2: Enable Google+ API

1. **Navigate to APIs & Services**
   - In the left sidebar, click "APIs & Services" → "Library"
   
2. **Enable Required APIs**
   - Search for "Google+ API" and enable it
   - Search for "Google Identity" and enable it

### Step 3: Create OAuth 2.0 Credentials

1. **Go to Credentials**
   - Click "APIs & Services" → "Credentials"
   
2. **Configure OAuth Consent Screen**
   - Click "OAuth consent screen" tab
   - Select "External" user type
   - Click "CREATE"
   - Fill in the required fields:
     - **App name**: Marriage Site
     - **User support email**: Your email
     - **Developer contact**: Your email
   - Click "SAVE AND CONTINUE"
   - Skip "Scopes" (click SAVE AND CONTINUE)
   - Add test users if needed
   - Click "SAVE AND CONTINUE"

3. **Create OAuth Client ID**
   - Go back to "Credentials" tab
   - Click "CREATE CREDENTIALS" → "OAuth client ID"
   - Select "Web application"
   - Enter name: `Marriage Site Web Client`
   - **Authorized JavaScript origins**:
     ```
     http://localhost
     ```
   - **Authorized redirect URIs**:
     ```
     http://localhost/weding/google_callback.php
     ```
   - Click "CREATE"

4. **Copy Your Credentials**
   - You'll see a popup with:
     - **Client ID**: Copy this
     - **Client Secret**: Copy this
   - Click "OK"

### Step 4: Configure Your Application

1. **Open the config file**
   - File: `includes/google_config.php`

2. **Replace the placeholder values**
   ```php
   define('GOOGLE_CLIENT_ID', 'YOUR_ACTUAL_CLIENT_ID_HERE');
   define('GOOGLE_CLIENT_SECRET', 'YOUR_ACTUAL_CLIENT_SECRET_HERE');
   ```

3. **Update redirect URI if needed**
   - If your project is not in `/weding/` folder, update:
   ```php
   define('GOOGLE_REDIRECT_URI', 'http://localhost/YOUR_FOLDER/google_callback.php');
   ```

### Step 5: Test Google Sign-In

1. **Start XAMPP**
   - Make sure Apache and MySQL are running

2. **Visit your site**
   - Go to: `http://localhost/weding/signin.php`
   - Click "Sign up with Google" button
   - You should be redirected to Google login

3. **Complete the flow**
   - Sign in with your Google account
   - Grant permissions
   - You'll be redirected back to your site

## 🔧 How It Works

### Authentication Flow:

1. **User clicks "Sign up with Google"**
   - Redirects to Google OAuth page
   - User logs in and grants permissions

2. **Google redirects back with code**
   - URL: `google_callback.php?code=XXXXX`
   - Code is exchanged for access token

3. **Get user information**
   - Access token is used to fetch user profile
   - Email, name, and picture are retrieved

4. **Create or login user**
   - **New user**: 
     - Stores Google data in session
     - Redirects to registration page (signin.php?google=1)
     - Email is pre-filled and locked
     - User completes gender and birth date
     - Account created with Google profile picture
     - Redirects to complete profile for additional details
   - **Existing user**: 
     - Logs them in directly
     - Updates last_seen timestamp
     - Redirects to dashboard

### Files Involved:

- **`includes/google_config.php`** - Configuration and helper functions
- **`google_callback.php`** - Handles OAuth callback
- **`signin.php`** - Registration page with Google button
- **`signup.php`** - Login page with Google button

## 🔒 Security Features

✅ **Secure token exchange** - Uses HTTPS for Google API calls
✅ **Password generation** - Random secure password for Google users
✅ **Session management** - Proper session handling
✅ **SQL injection protection** - Escaped database queries
✅ **Profile picture** - Automatically saves Google profile photo

## 🌐 For Production (Live Website)

When deploying to a live website:

1. **Update OAuth Consent Screen**
   - Change from "Testing" to "In Production"

2. **Update Authorized Origins**
   ```
   https://yourdomain.com
   ```

3. **Update Redirect URIs**
   ```
   https://yourdomain.com/google_callback.php
   ```

4. **Update config file**
   ```php
   define('GOOGLE_REDIRECT_URI', 'https://yourdomain.com/google_callback.php');
   ```

## 🐛 Troubleshooting

### Error: "redirect_uri_mismatch"
**Solution**: Make sure the redirect URI in Google Console exactly matches the one in your config file.

### Error: "Access blocked: This app's request is invalid"
**Solution**: Complete the OAuth consent screen configuration and add your email as a test user.

### Error: "Failed to get access token"
**Solution**: 
- Check that Client ID and Client Secret are correct
- Verify cURL is enabled in PHP (`php.ini`)
- Check internet connection

### Google button does nothing
**Solution**: 
- Check browser console for errors
- Verify the Google Auth URL is generated correctly
- Make sure `google_config.php` is included

### User created but can't login
**Solution**: 
- Google users don't have a password for manual login
- They must always use "Sign in with Google"
- Or complete profile and set a password

## 📝 Additional Notes

### For Users Who Sign Up with Google:
- They get a random password (they won't know it)
- They should always use "Sign in with Google"
- Profile picture is automatically imported from Google
- Email is verified (Google handles verification)

### Database Changes:
- No schema changes needed
- Google profile pictures stored in `user_photos` table
- Works with existing user structure

### Testing Accounts:
You can add test users in Google Console:
1. OAuth consent screen → Test users
2. Add email addresses
3. These users can sign in during testing phase

## ✅ Checklist

Before going live, ensure:
- [ ] Google Cloud project created
- [ ] OAuth consent screen configured
- [ ] OAuth credentials created
- [ ] Client ID and Secret added to config
- [ ] Redirect URI matches exactly
- [ ] Tested sign-up flow
- [ ] Tested sign-in flow
- [ ] Profile completion works
- [ ] Profile pictures display correctly

## 🎉 Success!

Once configured, users can:
- ✅ Sign up with one click using Google
- ✅ No need to remember passwords
- ✅ Automatic profile picture import
- ✅ Faster registration process
- ✅ More secure authentication

---

**Need Help?** 
- Google OAuth Documentation: https://developers.google.com/identity/protocols/oauth2
- Test your OAuth: https://developers.google.com/oauthplayground
