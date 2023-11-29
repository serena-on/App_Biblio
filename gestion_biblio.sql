-- This script creates a table named `Livres` with columns `id_livre`, `titre`, `auteur`, `résumé`, `disponibilité`, and `localisation`.
-- `id_livre` is an auto-incremented primary key.
-- `titre`, `auteur`, `résumé`, `disponibilité`, and `localisation` are all non-null columns with their respective data types.
-- `disponibilité` is an enumerated type with two possible values: 'disponible' and 'emprunté'.
CREATE TABLE `Livres`(
`id_livre` INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
`titre` varchar(30) NOT NULL,
`auteur` varchar(30) NOT NULL,
`résumé`  varchar(255) NOT NULL,
`disponibilité` enum('disponible','emprunté') NOT NULL,
`localisation`  varchar(30) NOT NULL);


CREATE TABLE `Étudiants`(
id_étudiant INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
nom varchar(30) NOT NULL,
prénom varchar(30) NOT NULL,
adresse varchar(30) NOT NULL, 
email varchar(30) NOT NULL,
téléphone varchar(30) NOT NULL
); 

CREATE TABLE `Emprunts`(
id_emprunt INT NOT NULL AUTO_INCREMENT,
id_livre INT NOT NULL,
id_etudiant INT NOT NULL,
date_emprunt date NOT NULL,
date_retour_prévue date NOT NULL,
date_retour_effective date,
PRIMARY KEY (id_emprunt, id_livre, id_etudiant),
FOREIGN KEY (id_livre) REFERENCES `Livres`(id_livre),
FOREIGN KEY (id_etudiant) REFERENCES `Étudiants`(id_étudiant),
CHECK (date_emprunt <= date_retour_prévue), 
CHECK (date_retour_effective >= date_emprunt)
);




INSERT INTO `Livres`(titre, auteur, résumé, disponibilité, localisation) 
VALUES ("Learn JavaScript from Scratch", "Béryl HOUESSOU", "Je ne sais pas à travers ce livre, je vous conduit
dans les profondeur du JavaScript. Avec moi apprenez à devenir des Héros en JavaScript en quittant, Zéro. Pas de prérequis nécessaire",
'disponible',
"Rayon: BEST SELLER, Pos: 03"
);

INSERT INTO `Livres`(titre, auteur, résumé, disponibilité, localisation) 
VALUES ("Learn Rust from Scratch", "Béryl HOUESSOU", "Je ne sais pas à travers ce livre, je vous conduit
dans les profondeur du Rust. Avec moi apprenez à devenir des Héros en Rust en quittant, Zéro. Pas de prérequis nécessaire.
Juste être un peu intelligent",
'emprunté',
"Rayon: CS, Pos: 01"
);

INSERT INTO `Étudiants`(nom, prénom, adresse,  email, téléphone)
VALUES (
    "AVOHOU",
    "Jonis",
    "VOSSA",
    "avohou.jonis@example.com",
    "00 00 00 00"
);

INSERT INTO `Étudiants`(nom, prénom, adresse,  email, téléphone)
VALUES (
    "GOUDALO",
    "Brice",
    "TOGBIN",
    "goudalo.brice@example.com",
    "01 01 02 02"
);

-- Liste of all enum values of a column 'disponibilité' in table 'Livres'
-- SELECT SUBSTRING(COLUMN_TYPE,5)
-- FROM information_schema.COLUMNS
-- WHERE TABLE_SCHEMA='gestion_biblio' 
--     AND TABLE_NAME='Livres'
--     AND COLUMN_NAME='disponibilité';

INSERT INTO `Emprunts`(id_livre, id_etudiant, date_emprunt, date_retour_prévue, date_retour_effective)
VALUES (1, 1, '2023-01-14', '2023-01-28', '2023-01-27');

INSERT INTO `Emprunts`(id_livre, id_etudiant, date_emprunt, date_retour_prévue, date_retour_effective)
VALUES (2, 1, '2023-01-14', '2023-01-28', '2023-01-27');


INSERT INTO `Emprunts`(id_livre, id_etudiant, date_emprunt, date_retour_prévue, date_retour_effective)
VALUES (2, 2, '2022-01-14', '2022-01-28', '2022-01-27');
