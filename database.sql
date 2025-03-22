CREATE DATABASE hotel_booking;
USE hotel_booking;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    firebase_id VARCHAR(255) UNIQUE NOT NULL, -- Matches 'uid' from frontend
    name VARCHAR(255),
    phone_number VARCHAR(20),
    email VARCHAR(255) UNIQUE NOT NULL,
    picture VARCHAR(255),
    address TEXT,
    pincode VARCHAR(10),
    date_of_birth VARCHAR(10),
    role ENUM('admin', 'user') DEFAULT 'user',
    password VARCHAR(255) -- Optional, not used with Firebase
);

CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    area DECIMAL(10, 2),
    price_per_night DECIMAL(10, 2),
    adult_max INT,
    children_max INT,
    description TEXT,
    status ENUM('active', 'inactive') DEFAULT 'active'
);

CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    room_id INT,
    check_in DATE,
    check_out DATE,
    total_price DECIMAL(10, 2),
    status ENUM('pending', 'confirmed', 'cancelled') DEFAULT 'pending',
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (room_id) REFERENCES rooms(id)
);

CREATE TABLE reviews (
    id INT AUTO_INCREMENT PRIMARY KEY,
    room_id INT,
    user_id INT,
    rating INT CHECK (rating >= 1 AND rating <= 5),
    review TEXT,
    date DATETIME,
    FOREIGN KEY (room_id) REFERENCES rooms(id),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE features (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE facilities (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR(255)
);

CREATE TABLE carousel (
    id INT AUTO_INCREMENT PRIMARY KEY,
    image VARCHAR(255) NOT NULL
);

CREATE TABLE settings (
    id INT PRIMARY KEY DEFAULT 1,
    website_title VARCHAR(255),
    about_us TEXT,
    shutdown ENUM('on', 'off') DEFAULT 'off',
    address TEXT,
    email VARCHAR(255),
    google_map_iframe TEXT
);

CREATE TABLE team_members (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    job_title VARCHAR(255),
    image VARCHAR(255)
);

INSERT INTO settings (id) VALUES (1);