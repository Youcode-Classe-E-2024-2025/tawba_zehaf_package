CREATE DATABASE package_management;

USE package_management;

CREATE TABLE auteurs (
    id_auteur INT AUTO_INCREMENT PRIMARY KEY,
    nom_auteur VARCHAR(255) NOT NULL,
    email VARCHAR(255) UNIQUE
);

CREATE TABLE packages (
    id_package INT AUTO_INCREMENT PRIMARY KEY,
    nom_package VARCHAR(255) NOT NULL,
    description TEXT,
    date_ajout TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE auteurs_packages (
    id_auteur INT,
    id_package INT,
    PRIMARY KEY (id_auteur, id_package),
    FOREIGN KEY (id_auteur) REFERENCES auteurs(id_auteur) ON DELETE CASCADE,
    FOREIGN KEY (id_package) REFERENCES packages(id_package) ON DELETE CASCADE
);

CREATE TABLE versions (
    id_version INT AUTO_INCREMENT PRIMARY KEY,
    id_package INT,
    version VARCHAR(50),
    date_release DATE,
    FOREIGN KEY (id_package) REFERENCES packages(id_package) ON DELETE CASCADE
);


CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('admin', 'user') NOT NULL
);

INSERT INTO auteurs (nom_auteur, email) VALUES
('Alice Johnson', 'alice.johnson@example.com'),
('Bob Smith', 'bob.smith@example.com'),
('Charlie Brown', 'charlie.brown@example.com'),
('David Green', 'david.green@example.com'),
('Eva White', 'eva.white@example.com'),
('Frank Harris', 'frank.harris@example.com'),
('Grace Lee', 'grace.lee@example.com'),
('Hannah Miller', 'hannah.miller@example.com'),
('Isaac Wilson', 'isaac.wilson@example.com'),
('Jack Davis', 'jack.davis@example.com');

INSERT INTO packages (nom_package, description, date_ajout) VALUES
('express', 'Fast, unopinionated, minimalist web framework for Node.js', '2024-01-01'),
('lodash', 'A modern utility library delivering modularity, performance & extras.', '2024-02-15'),
('react', 'A JavaScript library for building user interfaces', '2024-03-20'),
('axios', 'Promise based HTTP client for the browser and Node.js', '2024-04-10'),
('moment', 'Parse, validate, manipulate, and display dates and times in JavaScript', '2024-05-05'),
('chalk', 'Terminal string styling done right', '2024-06-12'),
('dotenv', 'Loads environment variables from .env for nodejs projects', '2024-07-18'),
('webpack', 'A static module bundler for modern JavaScript applications', '2024-08-22'),
('vue', 'A progressive JavaScript framework for building user interfaces', '2024-09-09'),
('babel', 'A JavaScript compiler for writing next generation JavaScript', '2024-10-25');

INSERT INTO versions (id_package, version, date_release) VALUES
(1, '4.18.1', '2024-01-01'),
(1, '4.18.0', '2024-01-15'),
(2, '4.17.21', '2024-02-15'),
(2, '4.17.20', '2024-03-05'),
(3, '18.2.0', '2024-03-20'),
(3, '18.1.0', '2024-04-01'),
(4, '0.21.1', '2024-04-10'),
(4, '0.21.0', '2024-04-20'),
(5, '2.29.1', '2024-05-05'),
(5, '2.29.0', '2024-05-15'),
(6, '5.2.3', '2024-06-12'),
(6, '5.2.2', '2024-06-22'),
(7, '16.0.0', '2024-07-18'),
(7, '15.9.9', '2024-07-28'),
(8, '5.74.0', '2024-08-22'),
(8, '5.73.0', '2024-09-01'),
(9, '3.2.0', '2024-09-09'),
(9, '3.1.5', '2024-09-19'),
(10, '7.8.0', '2024-10-25'),
(10, '7.7.5', '2024-11-05');

INSERT INTO auteurs_packages (id_auteur, id_package) VALUES
(1, 1), (2, 2), (3, 3), (4, 4), (5, 5), (6, 6), (7, 7), (8, 8), (9, 9), (10, 10);
