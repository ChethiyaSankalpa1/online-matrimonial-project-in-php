# Quick Setup Guide - Fix "Malformed Request" Error

## 🚨 Problem
Getting "The server cannot process the request because it is malformed" error when running SQL.

## ✅ Solution - Use PHP Setup Script (Easiest)

### Method 1: Automated PHP Setup (Recommended)

1. **Open your browser**
2. **Go to:** `http://localhost/weding/setup_database.php`
3. **Wait for it to complete** - You'll see green checkmarks ✓
4. **Done!** Click "Go to Search Page" to see users

**That's it!** The script automatically:
- Adds required columns
- Creates 6 sample users
- Adds profile data
- Adds 2 profile photos
- Sets up online status

---

## 🔧 Alternative Method: Manual SQL (If PHP doesn't work)

### Step-by-Step SQL Execution

1. **Open phpMyAdmin:** `http://localhost/phpmyadmin`
2. **Select database:** `marriage_site`
3. **Go to SQL tab**
4. **Run each query ONE BY ONE:**

#### Query 1: Add Columns
```sql
ALTER TABLE users ADD COLUMN last_seen TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE users ADD COLUMN city VARCHAR(100) DEFAULT NULL;
ALTER TABLE users ADD COLUMN country VARCHAR(100) DEFAULT NULL;
```

#### Query 2: Delete Old Users
```sql
DELETE FROM users WHERE email IN (
    'global@example.com', 
    'bluechip@example.com', 
    'dariush@example.com', 
    'buddika@example.com', 
    'channa@example.com', 
    'soulmate@example.com'
);
```

#### Query 3: Insert Users (Run each separately)
```sql
INSERT INTO users (username, email, password, gender, day, month, year, profile_completed, last_seen, city, country) VALUES
('Globalexplorer61', 'global@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 15, 6, 1992, 1, NOW(), 'Bandarangama', 'Sri Lanka');

INSERT INTO users (username, email, password, gender, day, month, year, profile_completed, last_seen, city, country) VALUES
('Bluechip', 'bluechip@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 20, 3, 1987, 1, DATE_SUB(NOW(), INTERVAL 2 HOUR), 'Colombo', 'Sri Lanka');

INSERT INTO users (username, email, password, gender, day, month, year, profile_completed, last_seen, city, country) VALUES
('Dariush007', 'dariush@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 10, 8, 1988, 1, DATE_SUB(NOW(), INTERVAL 1 HOUR), 'Colombo', 'Sri Lanka');

INSERT INTO users (username, email, password, gender, day, month, year, profile_completed, last_seen, city, country) VALUES
('Buddika93', 'buddika@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'female', 25, 5, 1994, 1, DATE_SUB(NOW(), INTERVAL 1 HOUR), 'Newport', 'United Kingdom');

INSERT INTO users (username, email, password, gender, day, month, year, profile_completed, last_seen, city, country) VALUES
('Channa23', 'channa@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 12, 2, 1978, 1, DATE_SUB(NOW(), INTERVAL 11 HOUR), 'Minneapolis', 'United States');

INSERT INTO users (username, email, password, gender, day, month, year, profile_completed, last_seen, city, country) VALUES
('Soulmateg', 'soulmate@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 8, 9, 1995, 1, DATE_SUB(NOW(), INTERVAL 11 HOUR), 'Winnipeg', 'Canada');
```

#### Query 4: Add Profile Data (Run each separately)
```sql
INSERT INTO user_data (user_id, height, status, education, career, religion, ethnicity) 
SELECT id, '5\'3" - 165 cm', 'Separated/Divorced', 'Masters degree', 'Engineering/Architecture', 'Buddhist', 'Sinhala but not Buddhist/Sinhalese' 
FROM users WHERE email = 'global@example.com';

-- Repeat for other users...
```

---

## 🎯 Recommended: Use the PHP Script!

**Why?**
- ✅ No SQL syntax errors
- ✅ Automatic error handling
- ✅ Shows progress with checkmarks
- ✅ One click setup
- ✅ Works every time

**Just visit:** `http://localhost/weding/setup_database.php`

---

## 📝 After Setup

1. **Visit search page:** `http://localhost/weding/search.php`
2. **You should see:**
   - 6 users with real data
   - Online status indicators (green/yellow/gray)
   - 2 users with profile photos
   - 4 users with default gender icons

3. **Delete setup file (optional):**
   - Delete `setup_database.php` after successful setup

---

## 🐛 Still Having Issues?

### Check:
1. **XAMPP is running** - Apache and MySQL both green
2. **Database exists** - `marriage_site` in phpMyAdmin
3. **Tables exist** - `users`, `user_data`, `user_photos`
4. **PHP version** - Should be 7.4 or higher

### Common Errors:

**"Column already exists"**
- That's okay! It means columns were added before
- Continue with next steps

**"Duplicate entry for email"**
- Run the DELETE query first (Query 2)
- Then insert users again

**"Unknown column"**
- Make sure Query 1 (ALTER TABLE) ran successfully
- Check if columns exist in phpMyAdmin

---

## ✅ Success Checklist

After setup, you should have:
- [ ] 6 sample users in database
- [ ] Online status working (different colors)
- [ ] Profile photos showing for 2 users
- [ ] Default icons for 4 users
- [ ] Search page displays all users
- [ ] Real-time online status

**All done!** 🎉
