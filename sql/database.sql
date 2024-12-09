
CREATE TABLE auteurs (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(100) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL
);

CREATE TABLE paquets (
    id SERIAL PRIMARY KEY,
    nom VARCHAR(150) UNIQUE NOT NULL,
    description TEXT,
    date_creation DATE DEFAULT CURRENT_DATE,
    auteur_id INTEGER NOT NULL,
    FOREIGN KEY (auteur_id) REFERENCES auteurs(id)
);

CREATE TABLE versions (
    id SERIAL PRIMARY KEY,
    paquet_id INTEGER NOT NULL,
    numero_version VARCHAR(25) NOT NULL,
    date_sortie DATE DEFAULT CURRENT_DATE,
    FOREIGN KEY (paquet_id) REFERENCES paquets(id)
);


CREATE TABLE contributions (
    paquet_id INTEGER NOT NULL,
    auteur_id INTEGER NOT NULL,
    PRIMARY KEY (paquet_id, auteur_id),
    FOREIGN KEY (paquet_id) REFERENCES paquets(id),
    FOREIGN KEY (auteur_id) REFERENCES auteurs(id)
);

INSERT INTO auteurs (nom, email)VALUES
('Jean Dupont', 'jean.dupont@email.com'),
('Marie Durand', 'marie.durand@email.com');


INSERT INTO paquets (nom, description, auteur_id)VALUES
('Paquet de calcul', 'Un paquet pour effectuer des calculs mathématiques.', 1),
('Paquet de gestion de données', 'Un paquet pour gérer des données utilisateurs.', 2);


 INSERT INTO versions (paquet_id, numero_version)VALUES
(1, '1.0.0'),
(1, '1.1.0'),
(2, '2.0.0');

INSERT INTO contributions (paquet_id, auteur_id)VALUES
(1, 1),
(1, 2),
(2, 2);
