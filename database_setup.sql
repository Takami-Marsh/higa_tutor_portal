CREATE TABLE tutors (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    salt VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    sex ENUM('male', 'female', 'other') NOT NULL,
    preferred_language ENUM('english', 'japanese', 'both') NOT NULL,
    university_choice ENUM('abroad', 'domestic', 'both') NOT NULL,
    subjects TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE students (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(255) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    salt VARCHAR(64) NOT NULL,
    name VARCHAR(255) NOT NULL,
    sex ENUM('male', 'female', 'other') NOT NULL,
    grade ENUM('grade9', 'grade10', 'grade11', 'grade12') NOT NULL,
    preferred_language ENUM('english', 'japanese', 'both') NOT NULL,
    university_choice ENUM('abroad', 'domestic', 'both') NOT NULL,
    subjects TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
