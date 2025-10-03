-- STEP 1: Add columns to users table
-- Run this first, if you get "column already exists" error, skip to STEP 2

ALTER TABLE users ADD COLUMN last_seen TIMESTAMP NULL DEFAULT NULL;
ALTER TABLE users ADD COLUMN city VARCHAR(100) DEFAULT NULL;
ALTER TABLE users ADD COLUMN country VARCHAR(100) DEFAULT NULL;

-- STEP 2: Delete old sample users (if they exist)
-- This prevents duplicate email errors

DELETE FROM users WHERE email IN (
    'global@example.com', 
    'bluechip@example.com', 
    'dariush@example.com', 
    'buddika@example.com', 
    'channa@example.com', 
    'soulmate@example.com'
);

-- STEP 3: Insert sample users

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

-- STEP 4: Insert user profile data

INSERT INTO user_data (user_id, height, status, education, career, religion, ethnicity) 
SELECT id, '5\'3" - 165 cm', 'Separated/Divorced', 'Masters degree', 'Engineering/Architecture', 'Buddhist', 'Sinhala but not Buddhist/Sinhalese' 
FROM users WHERE email = 'global@example.com';

INSERT INTO user_data (user_id, height, status, education, career, religion, ethnicity) 
SELECT id, '5\'9" - 175 cm', 'Never Married', 'Bachelors degree', 'Banking/Finance', 'Buddhist', 'Buddhist/Sinhalese (Southern)' 
FROM users WHERE email = 'bluechip@example.com';

INSERT INTO user_data (user_id, height, status, education, career, religion, ethnicity) 
SELECT id, '5\'8" - 173 cm', 'Separated/Divorced', 'Professional qualification', 'Transport/Manufacturing', 'Catholic', 'Catholic/Sinhalese (Western)' 
FROM users WHERE email = 'dariush@example.com';

INSERT INTO user_data (user_id, height, status, education, career, religion, ethnicity) 
SELECT id, '5\'6" - 173 cm', 'Never Married', 'Bachelors degree', 'Doctor/Medical Officer', 'Buddhist', 'Buddhist/Sinhalese' 
FROM users WHERE email = 'buddika@example.com';

INSERT INTO user_data (user_id, height, status, education, career, religion, ethnicity) 
SELECT id, '5\'10" - 178 cm', 'Separated/Divorced', 'Professional qualification', 'Other/Self Employed', 'Buddhist', 'Buddhist/Sinhalese' 
FROM users WHERE email = 'channa@example.com';

INSERT INTO user_data (user_id, height, status, education, career, religion, ethnicity) 
SELECT id, '5\'11" - 180 cm', 'Never Married', 'Masters degree', 'Engineering/Architecture', 'Buddhist', 'Buddhist/Sinhalese (Southern)' 
FROM users WHERE email = 'soulmate@example.com';

-- STEP 5: Add profile photos for 2 users

INSERT INTO user_photos (user_id, photo_path) 
SELECT id, 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=200&h=240&fit=crop' 
FROM users WHERE email = 'dariush@example.com';

INSERT INTO user_photos (user_id, photo_path) 
SELECT id, 'https://images.unsplash.com/photo-1494790108377-be9c29b29330?w=200&h=240&fit=crop' 
FROM users WHERE email = 'buddika@example.com';

-- Done! Check your search.php page to see the users
