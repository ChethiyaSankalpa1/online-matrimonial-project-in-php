-- Complete Database SQL for Marriage Site
-- Database: marriage_site

-- Drop database if exists and create new
DROP DATABASE IF EXISTS marriage_site;
CREATE DATABASE marriage_site;
USE marriage_site;

-- ============================================
-- Table 1: users (Main user accounts)
-- ============================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    gender ENUM('male', 'female') NOT NULL,
    day INT,
    month INT,
    year INT,
    profile_completed TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_gender (gender)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table 2: user_data (Extended user profile information)
-- ============================================
CREATE TABLE user_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    height VARCHAR(50),
    weight VARCHAR(50),
    figure VARCHAR(50),
    appearance VARCHAR(50),
    complexion VARCHAR(50),
    status VARCHAR(50),
    education VARCHAR(100),
    career VARCHAR(100),
    religion VARCHAR(50),
    ethnicity VARCHAR(50),
    caste VARCHAR(50),
    social_class VARCHAR(50),
    residency VARCHAR(100),
    family VARCHAR(100),
    smoking VARCHAR(50),
    drinking VARCHAR(50),
    children VARCHAR(50),
    personality VARCHAR(100),
    first_date_preference VARCHAR(100),
    living_arrangements VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table 3: user_photos (User uploaded photos)
-- ============================================
CREATE TABLE user_photos (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    photo_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table 4: user_friends (Friends/connections)
-- ============================================
CREATE TABLE user_friends (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    friend_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_friendship (user_id, friend_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (friend_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_friend_id (friend_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table 5: user_favourites (Favourite users)
-- ============================================
CREATE TABLE user_favourites (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    favourite_id INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    UNIQUE KEY unique_favourite (user_id, favourite_id),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (favourite_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_user_id (user_id),
    INDEX idx_favourite_id (favourite_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Table 6: chat_messages (Chat/messaging system)
-- ============================================
CREATE TABLE chat_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    sender_id INT NOT NULL,
    receiver_id INT NOT NULL,
    message TEXT NOT NULL,
    is_read TINYINT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_sender_id (sender_id),
    INDEX idx_receiver_id (receiver_id),
    INDEX idx_created_at (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- Sample Data (Optional - for testing)
-- ============================================

-- Insert sample users
INSERT INTO users (username, email, password, gender, day, month, year, profile_completed) VALUES
('John Doe', 'john@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 15, 6, 1995, 1),
('Jane Smith', 'jane@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'female', 20, 8, 1997, 1),
('Mike Johnson', 'mike@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'male', 10, 3, 1993, 1),
('Sarah Williams', 'sarah@example.com', '$2y$10$abcdefghijklmnopqrstuvwxyz123456789', 'female', 25, 12, 1996, 1);

-- Insert sample user data
INSERT INTO user_data (user_id, height, weight, figure, appearance, complexion, status, education, career, religion, ethnicity, caste, social_class, residency, family, smoking, drinking, children, personality, first_date_preference, living_arrangements) VALUES
(1, '5\'10"', '75kg', 'Athletic', 'Attractive', 'Fair', 'Single', 'Bachelor\'s Degree', 'Software Engineer', 'Christian', 'Caucasian', 'N/A', 'Middle Class', 'USA', 'Nuclear Family', 'Non-smoker', 'Social Drinker', 'No', 'Friendly, Outgoing', 'Coffee Shop', 'Living Alone'),
(2, '5\'6"', '60kg', 'Slim', 'Beautiful', 'Fair', 'Single', 'Master\'s Degree', 'Teacher', 'Christian', 'Caucasian', 'N/A', 'Middle Class', 'USA', 'Nuclear Family', 'Non-smoker', 'Non-drinker', 'No', 'Kind, Caring', 'Restaurant', 'Living with Family'),
(3, '6\'0"', '80kg', 'Athletic', 'Handsome', 'Medium', 'Single', 'Bachelor\'s Degree', 'Business Owner', 'Hindu', 'Asian', 'N/A', 'Upper Middle Class', 'India', 'Joint Family', 'Non-smoker', 'Social Drinker', 'No', 'Ambitious, Confident', 'Fine Dining', 'Living Alone'),
(4, '5\'5"', '55kg', 'Slim', 'Attractive', 'Fair', 'Single', 'Bachelor\'s Degree', 'Doctor', 'Muslim', 'Asian', 'N/A', 'Upper Middle Class', 'UK', 'Nuclear Family', 'Non-smoker', 'Non-drinker', 'No', 'Intelligent, Compassionate', 'Park Walk', 'Living with Roommates');

-- ============================================
-- Database Statistics and Info
-- ============================================
-- Total Tables: 6
-- Main Tables:
--   1. users - User accounts and authentication
--   2. user_data - Extended profile information
--   3. user_photos - Photo uploads
--   4. user_friends - Friend connections
--   5. user_favourites - Favourite users
--   6. chat_messages - Messaging system
-- ============================================

-- Show all tables
SHOW TABLES;

-- Show table structures
DESCRIBE users;
DESCRIBE user_data;
DESCRIBE user_photos;
DESCRIBE user_friends;
DESCRIBE user_favourites;
DESCRIBE chat_messages;
