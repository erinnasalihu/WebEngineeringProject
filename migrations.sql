DROP TABLE IF EXISTS recipes;
DROP TABLE IF EXISTS users;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    profile_photo MEDIUMBLOB,
    profile_photo_type VARCHAR(50),
    role ENUM('user', 'admin') NOT NULL DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- All passwords are 'olivepassword'
INSERT INTO users (username, email, password, role) VALUES 
-- Admins
('admin_sarah', 'sarah.admin@example.com', '$2y$12$xQlhd2lJIQ1USnGChi3okOU0Xy5WEOSfPRY7C3Enb9c3ITYME/AiG', 'admin'),
('admin_mike', 'mike.admin@example.com', '$2y$12$xQlhd2lJIQ1USnGChi3okOU0Xy5WEOSfPRY7C3Enb9c3ITYME/AiG', 'admin'),
('admin_lisa', 'lisa.admin@example.com', '$2y$12$xQlhd2lJIQ1USnGChi3okOU0Xy5WEOSfPRY7C3Enb9c3ITYME/AiG', 'admin'),
-- Regular users
('john_doe', 'john.doe@example.com', '$2y$12$xQlhd2lJIQ1USnGChi3okOU0Xy5WEOSfPRY7C3Enb9c3ITYME/AiG', 'user'),
('emma_wilson', 'emma.wilson@example.com', '$2y$12$xQlhd2lJIQ1USnGChi3okOU0Xy5WEOSfPRY7C3Enb9c3ITYME/AiG', 'user'),
('alex_chen', 'alex.chen@example.com', '$2y$12$xQlhd2lJIQ1USnGChi3okOU0Xy5WEOSfPRY7C3Enb9c3ITYME/AiG', 'user'),
('maria_garcia', 'maria.garcia@example.com', '$2y$12$xQlhd2lJIQ1USnGChi3okOU0Xy5WEOSfPRY7C3Enb9c3ITYME/AiG', 'user'),
('james_smith', 'james.smith@example.com', '$2y$12$xQlhd2lJIQ1USnGChi3okOU0Xy5WEOSfPRY7C3Enb9c3ITYME/AiG', 'user');

CREATE TABLE recipes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    ingredients TEXT,
    cooking_time INT,  -- in minutes
    category ENUM('sweet', 'savory') NOT NULL,
    image_url VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

INSERT INTO recipes (user_id, title, ingredients, cooking_time, category, image_url) VALUES 
(1, 'Classic Chocolate Chip Cookies', 'Flour, butter, chocolate chips, sugar, brown sugar, eggs, vanilla extract, baking soda, salt', 15, 'sweet', 'https://example.com/images/chocolate-chip-cookies.jpg'),
(1, 'Spaghetti Carbonara', 'Spaghetti, eggs, pecorino romano, guanciale, black pepper, salt', 25, 'savory', 'https://example.com/images/carbonara.jpg'),
(2, 'Banana Bread', 'Ripe bananas, flour, butter, sugar, eggs, baking soda, salt, vanilla extract', 60, 'sweet', 'https://example.com/images/banana-bread.jpg'),
(2, 'Chicken Stir Fry', 'Chicken breast, bell peppers, broccoli, soy sauce, garlic, ginger, vegetable oil, rice', 30, 'savory', 'https://example.com/images/stir-fry.jpg'),
(1, 'Lemon Cheesecake', 'Cream cheese, graham crackers, butter, sugar, eggs, lemon juice, lemon zest, vanilla extract', 75, 'sweet', 'https://example.com/images/lemon-cheesecake.jpg'),
(3, 'Beef Lasagna', 'Ground beef, lasagna noodles, ricotta cheese, mozzarella, tomato sauce, onion, garlic, herbs', 90, 'savory', 'https://example.com/images/lasagna.jpg'),
(2, 'Apple Pie', 'Apples, pie crust, cinnamon, sugar, butter, lemon juice, egg wash', 65, 'sweet', 'https://example.com/images/apple-pie.jpg'),
(3, 'Grilled Salmon', 'Salmon fillet, lemon, olive oil, garlic, dill, salt, pepper', 20, 'savory', 'https://example.com/images/grilled-salmon.jpg'),
(1, 'Chocolate Brownie', 'Dark chocolate, butter, eggs, sugar, flour, cocoa powder, vanilla extract', 35, 'sweet', 'https://example.com/images/brownie.jpg'),
(2, 'Mushroom Risotto', 'Arborio rice, mushrooms, onion, garlic, white wine, parmesan cheese, butter, vegetable stock', 45, 'savory', 'https://example.com/images/risotto.jpg');