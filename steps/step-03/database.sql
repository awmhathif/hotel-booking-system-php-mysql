-- Hotel booking system database.
-- In phpMyAdmin: create a database called hotel_db, open it, then run this file in the SQL tab.
-- Each table is followed by its demo data.

-- 1. Room types: what a guest books (Classic Double, Family Room...)
CREATE TABLE room_types (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(60) NOT NULL UNIQUE,
    description TEXT NOT NULL,
    price DECIMAL(8, 2) NOT NULL,
    max_guests INT NOT NULL,
    photo VARCHAR(100) NOT NULL
);

INSERT INTO room_types (name, description, price, max_guests, photo) VALUES
('Classic Single', 'A quiet room for one, facing the garden. Single bed, work desk and a rain shower.', 70.00, 1, 'single-1.jpg'),
('Classic Double', 'A double bed, a reading chair and a window over the courtyard.', 95.00, 2, 'double-1.jpg'),
('Lake View Double', 'A king bed facing a wide window over the lake, and a bath with a view.', 130.00, 2, 'lake-1.jpg'),
('Family Room', 'One double bed and two single beds in one bright room.', 160.00, 4, 'family-1.jpg'),
('Loft Suite', 'The whole top floor: bedroom, living room with a sofa bed and a deep bath.', 240.00, 3, 'loft-1.jpg');

-- 2. Rooms: the real rooms with a door number
CREATE TABLE rooms (
    id INT AUTO_INCREMENT PRIMARY KEY,
    number VARCHAR(10) NOT NULL UNIQUE,
    room_type_id INT NOT NULL,
    FOREIGN KEY (room_type_id) REFERENCES room_types(id)
);

INSERT INTO rooms (number, room_type_id) VALUES
('101', 1), ('102', 1), ('103', 2), ('104', 2), ('105', 2),
('201', 3), ('202', 3), ('203', 4), ('204', 4), ('301', 5);

-- 3. Messages from the contact form
CREATE TABLE messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(80) NOT NULL,
    email VARCHAR(120) NOT NULL,
    message TEXT NOT NULL,
    reply TEXT NULL,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO messages (name, email, message, reply) VALUES
('Nadia Perera', 'nadia@halden.test', 'Is there parking at the hotel?', 'Yes, we have free parking behind the hotel.'),
('Leo Brandt', 'leo@example.com', 'Do you allow pets?', NULL);
