-- Creating the database 'events_db'
CREATE DATABASE IF NOT EXISTS projects_ollyo_event_management_system;
USE projects_ollyo_event_management_system;

-- Users Table
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    image_url VARCHAR(155) NOT NULL,
    is_admin TINYINT(1) DEFAULT 0,
    is_active TINYINT(1) DEFAULT 1
);

-- Create indexes for users table
CREATE INDEX ollyo_event_idx_users_username ON users(username);
CREATE INDEX ollyo_event_idx_users_email ON users(email);
CREATE INDEX ollyo_event_idx_users_is_admin ON users(is_admin);
CREATE INDEX ollyo_event_idx_users_is_active ON users(is_active);

-- Events Table
CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    date_time DATETIME NOT NULL,
    capacity INT NOT NULL,
    attendees_count INT DEFAULT 0,
    image VARCHAR(155) NOT NULL,
    address VARCHAR(255) NOT NULL
);

-- Create indexes for events table
CREATE INDEX ollyo_event_idx_events_name ON events(name);
CREATE INDEX ollyo_event_idx_events_date_time ON events(date_time);
CREATE INDEX ollyo_event_idx_events_attendees_count ON events(attendees_count);
CREATE INDEX ollyo_event_idx_events_capacity ON events(capacity);

-- Attendees Table
CREATE TABLE attendees (
    id INT AUTO_INCREMENT PRIMARY KEY,
    event_id INT NOT NULL,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    FOREIGN KEY (event_id) REFERENCES events(id) ON DELETE CASCADE
);

-- Create indexes for attendees table
CREATE INDEX ollyo_event_idx_attendees_name ON attendees(name);
CREATE INDEX ollyo_event_idx_attendees_email ON attendees(email);



-- Inserting data into events table
INSERT INTO events (name, description, date_time, capacity, attendees_count, image, address)
VALUES 
('Tech Conference 2025', 'Tech Conference 2025 is a premier event that brings together some of the brightest minds in the tech industry to discuss the latest trends, innovations, and breakthroughs in technology. Attendees will have the opportunity to engage with thought leaders, participate in hands-on workshops, and explore cutting-edge solutions in fields like AI, cloud computing, cybersecurity, and blockchain. The conference is designed to inspire creativity, foster collaboration, and equip professionals with the knowledge they need to stay ahead of the curve in this ever-evolving industry.', '2025-03-15 09:00:00', 100, 50, 'tech_conference.jpg', '123 Tech Avenue, Dhaka, Bangladesh'),
('Art Workshop', 'This Art Workshop is designed for aspiring artists of all skill levels to explore their creative potential in a collaborative environment. Attendees will have the opportunity to learn various techniques, such as sketching, painting, and mixed media, from experienced artists and instructors. Whether you are looking to refine your existing skills or begin your journey into the world of art, this workshop provides a hands-on experience in a supportive atmosphere. Participants will also get the chance to showcase their work and receive feedback from their peers and mentors.', '2025-04-10 14:00:00', 80, 50, 'art_workshop.jpg', '45 Creative Lane, Dhaka, Bangladesh'),
('Startup Pitch Day', 'Startup Pitch Day is an exciting event where new and innovative startups from various industries present their business ideas to a panel of investors, potential partners, and industry experts. The event aims to connect entrepreneurs with the resources they need to grow their businesses, including funding, mentorship, and networking opportunities. Each startup will have the chance to pitch their idea in front of a live audience, followed by a Q&A session where they can showcase their vision and passion. This event is a must-attend for anyone interested in the startup ecosystem or looking for investment opportunities.', '2025-05-20 10:00:00', 50, 50, 'startup_pitch.jpg', '78 Entrepreneur Street, Dhaka, Bangladesh'),
('Music Concert', 'The Music Concert is set to be an unforgettable night filled with high-energy performances from top local and international bands and artists. Whether you’re into rock, pop, electronic, or indie music, this concert has something for everyone. The event will feature multiple stages, food trucks, and live performances that will keep you dancing and singing all night long. It’s a celebration of music and culture, bringing together diverse genres and musical styles in one spectacular event. Join us for a night of amazing tunes and great vibes under the stars.', '2025-06-05 19:00:00', 200, 50, 'music_concert.jpg', 'Music Arena, Dhaka, Bangladesh'),
('Health and Wellness Fair', 'The Health and Wellness Fair is an interactive event designed to promote a holistic approach to living a healthy and balanced life. Attendees will have access to a wide range of seminars, workshops, and activities focused on fitness, nutrition, mental health, and overall well-being. The fair will feature expert speakers, product demonstrations, and interactive sessions where participants can learn practical tips for improving their health and achieving a balanced lifestyle. Whether you’re looking to kickstart your fitness journey or learn new ways to manage stress, this fair offers valuable insights and resources to help you lead a healthier life.', '2025-07-12 08:00:00', 70, 50, 'health_fair.jpg', 'Green Park, Dhaka, Bangladesh');

-- Inserting data into attendees table
-- Inserting 50 attendees for 'Tech Conference 2025' (event_id = 1)
INSERT INTO attendees (event_id, name, email) 
VALUES
(1, 'John Doe', 'johndoe1@example.com'),
(1, 'Jane Smith', 'janesmith1@example.com'),
(1, 'Alex Johnson', 'alexjohnson1@example.com'),
(1, 'Emily Davis', 'emilydavis1@example.com'),
(1, 'Michael Brown', 'michaelbrown1@example.com'),
(1, 'Sophia Williams', 'sophiawilliams1@example.com'),
(1, 'William Martinez', 'williammartinez1@example.com'),
(1, 'Olivia Garcia', 'oliviagarcia1@example.com'),
(1, 'Liam Clark', 'liamclark1@example.com'),
(1, 'Isabella Lewis', 'isabellalewis1@example.com'),
(1, 'James Walker', 'jameswalker1@example.com'),
(1, 'Amelia Hall', 'ameliahall1@example.com'),
(1, 'Benjamin Allen', 'benjaminallen1@example.com'),
(1, 'Charlotte Young', 'charlottemartinez1@example.com'),
(1, 'Elijah King', 'elijahking1@example.com'),
(1, 'Evelyn Wright', 'evelynwright1@example.com'),
(1, 'Mason Scott', 'masonscott1@example.com'),
(1, 'Harper Adams', 'harperadams1@example.com'),
(1, 'Henry Nelson', 'henrynelson1@example.com'),
(1, 'Chloe Carter', 'chloecarter1@example.com'),
(1, 'Lucas Taylor', 'lucastaylor1@example.com'),
(1, 'Emma White', 'emmawhite1@example.com'),
(1, 'Sophia Clark', 'sophiaclark1@example.com'),
(1, 'David Anderson', 'davidanderson1@example.com'),
(1, 'Mason King', 'masonking1@example.com'),
(1, 'Olivia Turner', 'oliviaturner1@example.com'),
(1, 'Liam Mitchell', 'liammitchell1@example.com'),
(1, 'Benjamin Lee', 'benjaminlee1@example.com'),
(1, 'Amelia Carter', 'ameliacarter1@example.com'),
(1, 'Jackson Walker', 'jacksonwalker1@example.com'),
(1, 'Chloe Scott', 'chloescott1@example.com'),
(1, 'Isabella Wilson', 'isabellawilson1@example.com'),
(1, 'Ava Roberts', 'avaroberts1@example.com'),
(1, 'Sophia Harris', 'sophiaharris1@example.com'),
(1, 'Evelyn Harris', 'evelynharris1@example.com'),
(1, 'Charlotte Lewis', 'charlottelewis1@example.com'),
(1, 'Benjamin Martinez', 'benjaminmartinez1@example.com'),
(1, 'Liam Walker', 'liamwalker1@example.com'),
(1, 'Sophia Lewis', 'sophialewis1@example.com'),
(1, 'Mason Thompson', 'masonthompson1@example.com'),
(1, 'Olivia Roberts', 'oliviaroberts1@example.com'),
(1, 'James Mitchell', 'jamesmitchell1@example.com'),
(1, 'Ava Taylor', 'avataylor1@example.com'),
(1, 'Jackson Turner', 'jacksonturner1@example.com'),
(1, 'Ava Wilson', 'avawilson1@example.com'),
(1, 'Sophia Young', 'sophiayoung1@example.com'),
(1, 'Henry Adams', 'henryadams1@example.com');

-- Inserting 50 attendees for 'Art Workshop' (event_id = 2)
INSERT INTO attendees (event_id, name, email) 
VALUES
(2, 'John Doe', 'johndoe2@example.com'),
(2, 'Jane Smith', 'janesmith2@example.com'),
(2, 'Alex Johnson', 'alexjohnson2@example.com'),
(2, 'Emily Davis', 'emilydavis2@example.com'),
(2, 'Michael Brown', 'michaelbrown2@example.com'),
(2, 'Sophia Williams', 'sophiawilliams2@example.com'),
(2, 'William Martinez', 'williammartinez2@example.com'),
(2, 'Olivia Garcia', 'oliviagarcia2@example.com'),
(2, 'Liam Clark', 'liamclark2@example.com'),
(2, 'Isabella Lewis', 'isabellalewis2@example.com'),
(2, 'James Walker', 'jameswalker2@example.com'),
(2, 'Amelia Hall', 'ameliahall2@example.com'),
(2, 'Benjamin Allen', 'benjaminallen2@example.com'),
(2, 'Charlotte Young', 'charlottemartinez2@example.com'),
(2, 'Elijah King', 'elijahking2@example.com'),
(2, 'Evelyn Wright', 'evelynwright2@example.com'),
(2, 'Mason Scott', 'masonscott2@example.com'),
(2, 'Harper Adams', 'harperadams2@example.com'),
(2, 'Henry Nelson', 'henrynelson2@example.com'),
(2, 'Chloe Carter', 'chloecarter2@example.com'),
(2, 'Lucas Taylor', 'lucastaylor2@example.com'),
(2, 'Emma White', 'emmawhite2@example.com'),
(2, 'Sophia Clark', 'sophiaclark2@example.com'),
(2, 'David Anderson', 'davidanderson2@example.com'),
(2, 'Mason King', 'masonking2@example.com'),
(2, 'Olivia Turner', 'oliviaturner2@example.com'),
(2, 'Liam Mitchell', 'liammitchell2@example.com'),
(2, 'Benjamin Lee', 'benjaminlee2@example.com'),
(2, 'Amelia Carter', 'ameliacarter2@example.com'),
(2, 'Jackson Walker', 'jacksonwalker2@example.com'),
(2, 'Chloe Scott', 'chloescott2@example.com'),
(2, 'Isabella Wilson', 'isabellawilson2@example.com'),
(2, 'Ava Roberts', 'avaroberts2@example.com'),
(2, 'Sophia Harris', 'sophiaharris2@example.com'),
(2, 'Evelyn Harris', 'evelynharris2@example.com'),
(2, 'Charlotte Lewis', 'charlottelewis2@example.com'),
(2, 'Benjamin Martinez', 'benjaminmartinez2@example.com'),
(2, 'Liam Walker', 'liamwalker2@example.com'),
(2, 'Sophia Lewis', 'sophialewis2@example.com'),
(2, 'Mason Thompson', 'masonthompson2@example.com'),
(2, 'Olivia Roberts', 'oliviaroberts2@example.com'),
(2, 'James Mitchell', 'jamesmitchell2@example.com'),
(2, 'Ava Taylor', 'avataylor2@example.com'),
(2, 'Jackson Turner', 'jacksonturner2@example.com'),
(2, 'Ava Wilson', 'avawilson2@example.com'),
(2, 'Sophia Young', 'sophiayoung2@example.com'),
(2, 'Henry Adams', 'henryadams2@example.com');

-- Inserting 50 attendees for 'Startup Pitch Day' (event_id = 3)
INSERT INTO attendees (event_id, name, email) 
VALUES
(3, 'John Doe', 'johndoe3@example.com'),
(3, 'Jane Smith', 'janesmith3@example.com'),
(3, 'Alex Johnson', 'alexjohnson3@example.com'),
(3, 'Emily Davis', 'emilydavis3@example.com'),
(3, 'Michael Brown', 'michaelbrown3@example.com'),
(3, 'Sophia Williams', 'sophiawilliams3@example.com'),
(3, 'William Martinez', 'williammartinez3@example.com'),
(3, 'Olivia Garcia', 'oliviagarcia3@example.com'),
(3, 'Liam Clark', 'liamclark3@example.com'),
(3, 'Isabella Lewis', 'isabellalewis3@example.com'),
(3, 'James Walker', 'jameswalker3@example.com'),
(3, 'Amelia Hall', 'ameliahall3@example.com'),
(3, 'Benjamin Allen', 'benjaminallen3@example.com'),
(3, 'Charlotte Young', 'charlottemartinez3@example.com'),
(3, 'Elijah King', 'elijahking3@example.com'),
(3, 'Evelyn Wright', 'evelynwright3@example.com'),
(3, 'Mason Scott', 'masonscott3@example.com'),
(3, 'Harper Adams', 'harperadams3@example.com'),
(3, 'Henry Nelson', 'henrynelson3@example.com'),
(3, 'Chloe Carter', 'chloecarter3@example.com'),
(3, 'Lucas Taylor', 'lucastaylor3@example.com'),
(3, 'Emma White', 'emmawhite3@example.com'),
(3, 'Sophia Clark', 'sophiaclark3@example.com'),
(3, 'David Anderson', 'davidanderson3@example.com'),
(3, 'Mason King', 'masonking3@example.com'),
(3, 'Olivia Turner', 'oliviaturner3@example.com'),
(3, 'Liam Mitchell', 'liammitchell3@example.com'),
(3, 'Benjamin Lee', 'benjaminlee3@example.com'),
(3, 'Amelia Carter', 'ameliacarter3@example.com'),
(3, 'Jackson Walker', 'jacksonwalker3@example.com'),
(3, 'Chloe Scott', 'chloescott3@example.com'),
(3, 'Isabella Wilson', 'isabellawilson3@example.com'),
(3, 'Ava Roberts', 'avaroberts3@example.com'),
(3, 'Sophia Harris', 'sophiaharris3@example.com'),
(3, 'Evelyn Harris', 'evelynharris3@example.com'),
(3, 'Charlotte Lewis', 'charlottelewis3@example.com'),
(3, 'Benjamin Martinez', 'benjaminmartinez3@example.com'),
(3, 'Liam Walker', 'liamwalker3@example.com'),
(3, 'Sophia Lewis', 'sophialewis3@example.com'),
(3, 'Mason Thompson', 'masonthompson3@example.com'),
(3, 'Olivia Roberts', 'oliviaroberts3@example.com'),
(3, 'James Mitchell', 'jamesmitchell3@example.com'),
(3, 'Ava Taylor', 'avataylor3@example.com'),
(3, 'Jackson Turner', 'jacksonturner3@example.com'),
(3, 'Ava Wilson', 'avawilson3@example.com'),
(3, 'Sophia Young', 'sophiayoung3@example.com'),
(3, 'Henry Adams', 'henryadams3@example.com');

-- Inserting 50 attendees for 'Developer Bootcamp' (event_id = 4)
INSERT INTO attendees (event_id, name, email) 
VALUES
(4, 'John Doe', 'johndoe4@example.com'),
(4, 'Jane Smith', 'janesmith4@example.com'),
(4, 'Alex Johnson', 'alexjohnson4@example.com'),
(4, 'Emily Davis', 'emilydavis4@example.com'),
(4, 'Michael Brown', 'michaelbrown4@example.com'),
(4, 'Sophia Williams', 'sophiawilliams4@example.com'),
(4, 'William Martinez', 'williammartinez4@example.com'),
(4, 'Olivia Garcia', 'oliviagarcia4@example.com'),
(4, 'Liam Clark', 'liamclark4@example.com'),
(4, 'Isabella Lewis', 'isabellalewis4@example.com'),
(4, 'James Walker', 'jameswalker4@example.com'),
(4, 'Amelia Hall', 'ameliahall4@example.com'),
(4, 'Benjamin Allen', 'benjaminallen4@example.com'),
(4, 'Charlotte Young', 'charlottemartinez4@example.com'),
(4, 'Elijah King', 'elijahking4@example.com'),
(4, 'Evelyn Wright', 'evelynwright4@example.com'),
(4, 'Mason Scott', 'masonscott4@example.com'),
(4, 'Harper Adams', 'harperadams4@example.com'),
(4, 'Henry Nelson', 'henrynelson4@example.com'),
(4, 'Chloe Carter', 'chloecarter4@example.com'),
(4, 'Lucas Taylor', 'lucastaylor4@example.com'),
(4, 'Emma White', 'emmawhite4@example.com'),
(4, 'Sophia Clark', 'sophiaclark4@example.com'),
(4, 'David Anderson', 'davidanderson4@example.com'),
(4, 'Mason King', 'masonking4@example.com'),
(4, 'Olivia Turner', 'oliviaturner4@example.com'),
(4, 'Liam Mitchell', 'liammitchell4@example.com'),
(4, 'Benjamin Lee', 'benjaminlee4@example.com'),
(4, 'Amelia Carter', 'ameliacarter4@example.com'),
(4, 'Jackson Walker', 'jacksonwalker4@example.com'),
(4, 'Chloe Scott', 'chloescott4@example.com'),
(4, 'Isabella Wilson', 'isabellawilson4@example.com'),
(4, 'Ava Roberts', 'avaroberts4@example.com'),
(4, 'Sophia Harris', 'sophiaharris4@example.com'),
(4, 'Evelyn Harris', 'evelynharris4@example.com'),
(4, 'Charlotte Lewis', 'charlottelewis4@example.com'),
(4, 'Benjamin Martinez', 'benjaminmartinez4@example.com'),
(4, 'Liam Walker', 'liamwalker4@example.com'),
(4, 'Sophia Lewis', 'sophialewis4@example.com'),
(4, 'Mason Thompson', 'masonthompson4@example.com'),
(4, 'Olivia Roberts', 'oliviaroberts4@example.com'),
(4, 'James Mitchell', 'jamesmitchell4@example.com'),
(4, 'Ava Taylor', 'avataylor4@example.com'),
(4, 'Jackson Turner', 'jacksonturner4@example.com'),
(4, 'Ava Wilson', 'avawilson4@example.com'),
(4, 'Sophia Young', 'sophiayoung4@example.com'),
(4, 'Henry Adams', 'henryadams4@example.com');

-- Inserting 50 attendees for 'Creative Coding Workshop' (event_id = 5)
INSERT INTO attendees (event_id, name, email) 
VALUES
(5, 'John Doe', 'johndoe5@example.com'),
(5, 'Jane Smith', 'janesmith5@example.com'),
(5, 'Alex Johnson', 'alexjohnson5@example.com'),
(5, 'Emily Davis', 'emilydavis5@example.com'),
(5, 'Michael Brown', 'michaelbrown5@example.com'),
(5, 'Sophia Williams', 'sophiawilliams5@example.com'),
(5, 'William Martinez', 'williammartinez5@example.com'),
(5, 'Olivia Garcia', 'oliviagarcia5@example.com'),
(5, 'Liam Clark', 'liamclark5@example.com'),
(5, 'Isabella Lewis', 'isabellalewis5@example.com'),
(5, 'James Walker', 'jameswalker5@example.com'),
(5, 'Amelia Hall', 'ameliahall5@example.com'),
(5, 'Benjamin Allen', 'benjaminallen5@example.com'),
(5, 'Charlotte Young', 'charlottemartinez5@example.com'),
(5, 'Elijah King', 'elijahking5@example.com'),
(5, 'Evelyn Wright', 'evelynwright5@example.com'),
(5, 'Mason Scott', 'masonscott5@example.com'),
(5, 'Harper Adams', 'harperadams5@example.com'),
(5, 'Henry Nelson', 'henrynelson5@example.com'),
(5, 'Chloe Carter', 'chloecarter5@example.com'),
(5, 'Lucas Taylor', 'lucastaylor5@example.com'),
(5, 'Emma White', 'emmawhite5@example.com'),
(5, 'Sophia Clark', 'sophiaclark5@example.com'),
(5, 'David Anderson', 'davidanderson5@example.com'),
(5, 'Mason King', 'masonking5@example.com'),
(5, 'Olivia Turner', 'oliviaturner5@example.com'),
(5, 'Liam Mitchell', 'liammitchell5@example.com'),
(5, 'Benjamin Lee', 'benjaminlee5@example.com'),
(5, 'Amelia Carter', 'ameliacarter5@example.com'),
(5, 'Jackson Walker', 'jacksonwalker5@example.com'),
(5, 'Chloe Scott', 'chloescott5@example.com'),
(5, 'Isabella Wilson', 'isabellawilson5@example.com'),
(5, 'Ava Roberts', 'avaroberts5@example.com'),
(5, 'Sophia Harris', 'sophiaharris5@example.com'),
(5, 'Evelyn Harris', 'evelynharris5@example.com'),
(5, 'Charlotte Lewis', 'charlottelewis5@example.com'),
(5, 'Benjamin Martinez', 'benjaminmartinez5@example.com'),
(5, 'Liam Walker', 'liamwalker5@example.com'),
(5, 'Sophia Lewis', 'sophialewis5@example.com'),
(5, 'Mason Thompson', 'masonthompson5@example.com'),
(5, 'Olivia Roberts', 'oliviaroberts5@example.com'),
(5, 'James Mitchell', 'jamesmitchell5@example.com'),
(5, 'Ava Taylor', 'avataylor5@example.com'),
(5, 'Jackson Turner', 'jacksonturner5@example.com'),
(5, 'Ava Wilson', 'avawilson5@example.com'),
(5, 'Sophia Young', 'sophiayoung5@example.com'),
(5, 'Henry Adams', 'henryadams5@example.com');


-- Inserting users
INSERT INTO users (username, email, password_hash, image_url, is_admin, is_active) 
VALUES 
('admin', 'admin@admin.com', '$2y$10$JUZrNoPrXbGWg9Y8pVCDPuJ9OosvKQSolIs5egiDSojbp8GASG0UO', 'admin.jpg', 1, 1),
('user1', 'user1@user.com', '$2y$10$lcR.9acUkuLlvwJVMdxce.Z92RjZwIasuQvpe6VPj0vsL2zps3Es6', 'avatar1.png', 0, 1),
('user2', 'user2@user.com', '$2y$10$lcR.9acUkuLlvwJVMdxce.Z92RjZwIasuQvpe6VPj0vsL2zps3Es6', 'avatar2.png', 0, 1);