# Setup Instructions for Real User Search with Online Status

## Database Setup

### Step 1: Run the SQL Update Script
Execute the following SQL file to add online status tracking to your database:

```bash
# Open phpMyAdmin or MySQL command line
# Navigate to: http://localhost/phpmyadmin
# Select your database: marriage_site
# Go to SQL tab and run the file:
```

**File to run:** `database/add_online_status.sql`

This will:
- Add `last_seen`, `city`, and `country` columns to the users table
- Insert 6 sample users with Sri Lankan theme
- Add corresponding user profile data
- Set up initial online status timestamps

### Step 2: Verify Database Changes

Check that the following columns exist in the `users` table:
- `last_seen` (TIMESTAMP)
- `city` (VARCHAR)
- `country` (VARCHAR)

## Features Implemented

### 1. **Real User Data**
- Search page now fetches real users from the database
- Displays actual user information (name, age, location, education, career, etc.)
- Shows dynamic profile count
- **Profile Photos**: Displays user photos if available, otherwise shows gender-based default icons (👨 for male, 👩 for female)

### 2. **Online Status Tracking**
The system tracks user activity with color-coded indicators:

- **🟢 Green (Online)**: User active within last 5 minutes - shows "just now"
- **🟡 Yellow (Recent)**: User active within last 24 hours - shows "X hours ago"
- **⚪ Gray (Offline)**: User inactive for more than 24 hours - shows "X days ago"

### 3. **Auto-Update Features**
- **Logged-in users**: Status updates every 2 minutes automatically
- **Page refresh**: Auto-refreshes every 5 minutes to show latest online statuses
- **Real-time tracking**: `update_online_status.php` handles status updates

### 4. **Login Restrictions** (Previously Implemented)
- Non-logged users can only view profile information
- All interactive features (chat, mail, interest, favorites) require sign-up
- Beautiful modal popup with Yes/No options to encourage registration

## Files Created/Modified

### New Files:
1. `database/add_online_status.sql` - Database update script
2. `update_online_status.php` - Online status updater
3. `SETUP_INSTRUCTIONS.md` - This file

### Modified Files:
1. `search.php` - Now displays real users with online status

## How It Works

### Online Status Logic:
```php
- Last seen < 5 minutes = "just now" (Green)
- Last seen < 60 minutes = "X minutes ago" (Yellow)
- Last seen < 24 hours = "X hours ago" (Yellow)
- Last seen > 24 hours = "X days ago" (Gray)
```

### User Activity Tracking:
- When a logged-in user visits any page, their `last_seen` timestamp updates
- JavaScript pings the server every 2 minutes to keep status current
- Other users see updated statuses when the page refreshes (every 5 minutes)

## Testing

1. **Run the SQL script** to add sample users
2. **Visit search.php** to see real users displayed
3. **Log in as a user** to see your online status update
4. **Open in another browser** (not logged in) to see how non-logged users experience it
5. **Wait a few minutes** and refresh to see status changes

## Sample Users Added

The SQL script adds these users:
- Globalexplorer61 (Online now) - Default male icon
- Bluechip (2 hours ago) - Default male icon
- Dariush007 (1 hour ago) - **Has profile photo**
- Buddika93 (1 hour ago) - **Has profile photo** (female)
- Channa23 (11 hours ago) - Default male icon
- Soulmateg (11 hours ago) - Default male icon

**Note**: 2 users have sample profile photos, 4 users show default gender icons

## Customization

### Change Refresh Intervals:
Edit `search.php`:
```javascript
// Status update interval (currently 2 minutes)
setInterval(function() { ... }, 120000);

// Page refresh interval (currently 5 minutes)
setInterval(function() { ... }, 300000);
```

### Modify Online Threshold:
Edit the `getOnlineStatus()` function in `search.php`:
```php
elseif ($minutes_ago < 5) { // Change 5 to your preferred minutes
    return ['text' => 'just now', 'class' => 'online'];
}
```

## Troubleshooting

**Issue**: No users showing
- **Solution**: Run the SQL script `add_online_status.sql`

**Issue**: Online status not updating
- **Solution**: Check that `update_online_status.php` is accessible and user is logged in

**Issue**: Database connection error
- **Solution**: Verify `database/db_connect.php` has correct credentials

**Issue**: Users show as offline
- **Solution**: Log in as those users to update their `last_seen` timestamp

## Adding Profile Photos

### For Real User Photos:
1. Create an `uploads/profiles/` directory in your project
2. Upload user photos to this directory
3. Insert photo paths into `user_photos` table:
```sql
INSERT INTO user_photos (user_id, photo_path) VALUES
(1, 'uploads/profiles/user1.jpg');
```

### Photo Display Logic:
- If user has a photo in `user_photos` table → Display the photo
- If no photo exists → Show default gender icon (👨 for male, 👩 for female)

### Supported Photo Formats:
- Local files: `uploads/profiles/photo.jpg`
- External URLs: `https://example.com/photo.jpg`

## Next Steps

Consider adding:
- Advanced search filters (age range, location, marital status)
- Pagination for large user lists
- Photo upload functionality in user profile
- More detailed online status (e.g., "typing...", "viewing your profile")
- Push notifications for new matches
- Multiple photos per user (photo gallery)
