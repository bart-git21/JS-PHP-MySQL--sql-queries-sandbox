CREATE DATABASE IF NOT EXISTS sql_queries;
USE sql_queries;
CREATE TABLE IF NOT EXISTS users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    login TEXT NOT NULL,
    password TEXT NOT NULL,
    role TEXT NOT NULL
);
CREATE TABLE IF NOT EXISTS queries (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name TEXT NOT NULL,
    query TEXT NOT NULL,
    user_id INT NOT NULL
);
CREATE TABLE IF NOT EXISTS test_data (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name TEXT NOT NULL,
    diameter_km INT NOT NULL,
    distance_from_sun_million_km FLOAT(5,1) NOT NULL,
    first_visited_year INT
);
INSERT INTO users (login, password, role) VALUES ('admin', 'admin', 'admin'), ('Item1', '123', 'user'), ('Item2', '456', 'user');
INSERT INTO queries (name, query, user_id) VALUES ('get all planets', 'SELECT * FROM test_data', 2), ('get Earth data', 'SELECT * FROM test_data WHERE id = 3', 3);
INSERT INTO test_data (id, name, diameter_km, distance_from_sun_million_km, first_visited_year) VALUES (1, 'Mercury', 4879, 57.9, NULL), (2, 'Venus', 12104, 108.2, NULL), (3, 'Earth', 12756, 149.6, 1969), (4, 'Mars', 6792, 227.9, 1965), (5, 'Jupiter', 142984, 778.6, 1973), (6, 'Saturn', 120536, 1433.5, 1979), (7, 'Uranus', 51118, 2872.5, 1986), (8, 'Neptune', 49528, 4495.1, 1989);
