INSERT INTO users_types (user_type_id, name)
VALUES (3, 'Admin');
INSERT INTO users (pseudo, email, password, user_type_id, departement)
VALUES ('Admin', 'admin@gmail.com', 'admin', 3, '83');