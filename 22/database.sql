-- Portfolio Management System Database Schema with Multilingual Support

-- Admin users table
CREATE TABLE IF NOT EXISTS admin_users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- About Me section with language support
CREATE TABLE IF NOT EXISTS about_me (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name_en VARCHAR(100) NOT NULL,
    name_ku VARCHAR(100) NOT NULL,
    title_en VARCHAR(200) NOT NULL,
    title_ku VARCHAR(200) NOT NULL,
    description_en TEXT NOT NULL,
    description_ku TEXT NOT NULL,
    university_en VARCHAR(200),
    university_ku VARCHAR(200),
    skills_en TEXT,
    skills_ku TEXT,
    profile_image VARCHAR(500),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Projects table with language support
CREATE TABLE IF NOT EXISTS projects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(200) NOT NULL,
    title_ku VARCHAR(200) NOT NULL,
    description_en TEXT NOT NULL,
    description_ku TEXT NOT NULL,
    category ENUM('completed', 'ongoing', 'concept') NOT NULL,
    technologies_en VARCHAR(300),
    technologies_ku VARCHAR(300),
    image_url VARCHAR(500),
    demo_url VARCHAR(500),
    github_url VARCHAR(500),
    project_link VARCHAR(500),
    project_link_text_en VARCHAR(200),
    project_link_text_ku VARCHAR(200),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
status ENUM('completed', 'ongoing', 'concept') DEFAULT 'completed'
);

-- Reports table with language support
CREATE TABLE IF NOT EXISTS reports (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(200) NOT NULL,
    title_ku VARCHAR(200) NOT NULL,
    description_en TEXT,
    description_ku TEXT,
    file_url VARCHAR(500),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Experience table with language support
CREATE TABLE IF NOT EXISTS experience (
    id INT AUTO_INCREMENT PRIMARY KEY,
    company_en VARCHAR(200) NOT NULL,
    company_ku VARCHAR(200) NOT NULL,
    position_en VARCHAR(200) NOT NULL,
    position_ku VARCHAR(200) NOT NULL,
    description_en TEXT,
    description_ku TEXT,
    year VARCHAR(20),
    image_url VARCHAR(500),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Achievements table with language support
CREATE TABLE IF NOT EXISTS achievements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(200) NOT NULL,
    title_ku VARCHAR(200) NOT NULL,
    description_en TEXT,
    description_ku TEXT,
    year VARCHAR(20),
    image_url VARCHAR(500),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Contact information table
CREATE TABLE IF NOT EXISTS contact_info (
    id INT AUTO_INCREMENT PRIMARY KEY,
    linkedin VARCHAR(300),
    email VARCHAR(100),
    phone VARCHAR(50),
    cv_url VARCHAR(500),
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Site settings table with language support
CREATE TABLE IF NOT EXISTS site_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    setting_key VARCHAR(100) UNIQUE NOT NULL,
    setting_value_en TEXT,
    setting_value_ku TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create certificates table with language support
CREATE TABLE IF NOT EXISTS certificates (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255) NOT NULL,
    title_ku VARCHAR(255) NOT NULL,
    description_en TEXT,
    description_ku TEXT,
    issuing_organization VARCHAR(255),
    issue_date DATE,
    serial_number VARCHAR(100),
    image_path VARCHAR(500),
    display_order INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Create cv_resumes table with language support
CREATE TABLE IF NOT EXISTS cv_resumes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title_en VARCHAR(255) NOT NULL,
    title_ku VARCHAR(255) NOT NULL,
    file_path VARCHAR(500) NOT NULL,
    is_active BOOLEAN DEFAULT 1,
    display_order INT DEFAULT 0,
    language ENUM('both', 'en', 'ku') DEFAULT 'both',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert default admin user (password: admin123)
INSERT INTO admin_users (username, password) VALUES 
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi');

-- Insert default about me data
INSERT INTO about_me (name_en, name_ku, title_en, title_ku, description_en, description_ku, university_en, university_ku, skills_en, skills_ku) VALUES 
('Your Name', 'ناوی تۆ', 'Programmer and AI & Robotics Expert', 'پرۆگرامەر و پسپۆڕی زیرەکی دەستکرد و ڕۆبۆتیک', 'Computer Engineering student passionate about AI and technology.', 'خوێندکاری ئەندازیاری کۆمپیوتەر، خولیای زیرەکی دەستکرد و تەکنەلۆژیا.', 'Your University', 'زانکۆی تۆ', 'Python,C++,AI,Robotics,HTML,CSS', 'پایسۆن,سی++,زیرەکی دەستکرد,ڕۆبۆتیک,HTML,CSS');

-- Insert default contact info
INSERT INTO contact_info (linkedin, email, phone, cv_url) VALUES 
('', 'your-email@example.com', '', '');

-- Insert default site settings
INSERT INTO site_settings (setting_key, setting_value_en, setting_value_ku) VALUES 
('site_title', 'My Portfolio', 'پۆرتفۆلیۆی من'),
('welcome_message', 'Hello, welcome to my portfolio', 'سڵاو، بەخێربێیت بۆ پۆرتفۆلیۆی من'),
('hero_subtitle', 'Programmer and AI & Robotics Expert', 'پرۆگرامەر و پسپۆڕی زیرەکی دەستکرد و ڕۆبۆتیک');

-- Insert sample certificate data (optional)
INSERT INTO certificates (title_en, title_ku, description_en, description_ku, issuing_organization, issue_date, display_order) VALUES 
('Sample Certificate', 'نموونەی بڕوانامە', 'Sample certificate description', 'وەسفی نموونەی بڕوانامە', 'Sample Organization', CURRENT_DATE, 1);

-- Insert sample CV data (optional)
INSERT INTO cv_resumes (title_en, title_ku, file_path, is_active) VALUES 
('Latest CV', 'دوایین CV', 'uploads/cv/sample-cv.pdf', 1);