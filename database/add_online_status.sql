-- Add last_seen column to users table for online status tracking
USE marriage_site;

-- Add last_seen column if it doesn't exist
-- Note: If columns already exist, you may get an error - that's okay, just continue
ALTER TABLE users 
ADD COLUMN last_seen TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE users 
ADD COLUMN city VARCHAR(100) DEFAULT NULL;

ALTER TABLE users 
ADD COLUMN country VARCHAR(100) DEFAULT NULL;

-- Update existing users with some sample data
UPDATE users SET last_seen = NOW() WHERE id = 1;
UPDATE users SET last_seen = DATE_SUB(NOW(), INTERVAL 2 HOUR) WHERE id = 2;
UPDATE users SET last_seen = DATE_SUB(NOW(), INTERVAL 1 HOUR) WHERE id = 3;
UPDATE users SET last_seen = DATE_SUB(NOW(), INTERVAL 11 HOUR) WHERE id = 4;

-- Add city and country data
UPDATE users SET city = 'Colombo', country = 'Sri Lanka' WHERE id = 1;
UPDATE users SET city = 'Bandarangama', country = 'Sri Lanka' WHERE id = 2;
UPDATE users SET city = 'Newport', country = 'United Kingdom' WHERE id = 3;
UPDATE users SET city = 'Minneapolis', country = 'United States' WHERE id = 4;

-- Delete existing sample users if they exist (to avoid duplicate errors)
DELETE FROM users WHERE email IN ('global@example.com', 'bluechip@example.com', 'dariush@example.com', 'buddika@example.com', 'channa@example.com', 'soulmate@example.com');

-- Insert more sample users with Sri Lankan theme
INSERT INTO users (username, email, password, gender, day, month, year, profile_completed, last_seen, city, country) VALUES
('Globalexplorer61', 'global@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 15, 6, 1992, 1, NOW(), 'Bandarangama', 'Sri Lanka'),
('Bluechip', 'bluechip@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 20, 3, 1987, 1, DATE_SUB(NOW(), INTERVAL 2 HOUR), 'Colombo', 'Sri Lanka'),
('Dariush007', 'dariush@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 10, 8, 1988, 1, DATE_SUB(NOW(), INTERVAL 1 HOUR), 'Colombo', 'Sri Lanka'),
('Buddika93', 'buddika@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'female', 25, 5, 1994, 1, DATE_SUB(NOW(), INTERVAL 1 HOUR), 'Newport', 'United Kingdom'),
('Channa23', 'channa@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 12, 2, 1978, 1, DATE_SUB(NOW(), INTERVAL 11 HOUR), 'Minneapolis', 'United States'),
('Soulmateg', 'soulmate@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 8, 9, 1995, 1, DATE_SUB(NOW(), INTERVAL 11 HOUR), 'Winnipeg', 'Canada');

-- Insert corresponding user_data for new users (using email to find user_id)
INSERT INTO user_data (user_id, height, status, education, career, religion, ethnicity) 
SELECT id, '5\'3" - 165 cm', 'Separated/Divorced', 'Masters degree', 'Engineering/Architecture', 'Buddhist', 'Sinhala but not Buddhist/Sinhalese' FROM users WHERE email = 'global@example.com'
UNION ALL
SELECT id, '5\'9" - 175 cm', 'Never Married', 'Bachelors degree', 'Banking/Finance', 'Buddhist', 'Buddhist/Sinhalese (Southern)' FROM users WHERE email = 'bluechip@example.com'
UNION ALL
SELECT id, '5\'8" - 173 cm', 'Separated/Divorced', 'Professional qualification', 'Transport/Manufacturing', 'Catholic', 'Catholic/Sinhalese (Western)' FROM users WHERE email = 'dariush@example.com'
UNION ALL
SELECT id, '5\'6" - 173 cm', 'Never Married', 'Bachelors degree', 'Doctor/Medical Officer', 'Buddhist', 'Buddhist/Sinhalese' FROM users WHERE email = 'buddika@example.com'
UNION ALL
SELECT id, '5\'10" - 178 cm', 'Separated/Divorced', 'Professional qualification', 'Other/Self Employed', 'Buddhist', 'Buddhist/Sinhalese' FROM users WHERE email = 'channa@example.com'
UNION ALL
SELECT id, '5\'11" - 180 cm', 'Never Married', 'Masters degree', 'Engineering/Architecture', 'Buddhist', 'Buddhist/Sinhalese (Southern)' FROM users WHERE email = 'soulmate@example.com';

-- Add sample profile photos (using placeholder image URLs)
-- Note: You can replace these with actual uploaded photos later
INSERT INTO user_photos (user_id, photo_path) 
SELECT id, 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=240&fit=crop' FROM users WHERE email = 'dariush@example.com'
UNION ALL
SELECT id, 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=240&fit=crop' FROM users WHERE email = 'buddika@example.com';

-- Users Globalexplorer61, Bluechip, Channa23, Soulmateg will show default gender icons (no photos)
-- Users Dariush007 and Buddika93 have profile photos

SELECT 'Database updated successfully with online status tracking and profile photos!' as message;
