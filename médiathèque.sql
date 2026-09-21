

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


--

CREATE TABLE `adherent` (
  `id_adherent` int(11) NOT NULL,
  `nom` varchar(80) NOT NULL,
  `prenom` varchar(80) NOT NULL,
  `email` varchar(150) NOT NULL,
  `date_inscription` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `adherent` (`id_adherent`, `nom`, `prenom`, `email`, `date_inscription`) VALUES
(1, 'Ali', 'Youssouf', 'youssouf.ali@example.test', '2026-01-12'),
(2, 'Hassan', 'Amina', 'amina.hassan@example.test', '2026-02-03'),
(3, 'Omar', 'Sarah', 'sarah.omar@example.test', '2026-02-21'),
(4, 'Aden', 'Mohamed', 'mohamed.aden@example.test', '2026-03-15'),
(5, 'Farah', 'Ilhan', 'ilhan.farah@example.test', '2026-04-08'),
(6, 'Robleh', 'Kamil', 'kamil.robleh@example.test', '2026-05-19');


CREATE TABLE `auteur` (
  `id_auteur` int(11) NOT NULL,
  `nom` varchar(80) NOT NULL,
  `prenom` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;




INSERT INTO `auteur` (`id_auteur`, `nom`, `prenom`) VALUES
(1, 'Martin', 'Claire'),
(2, 'Diallo', 'Moussa'),
(3, 'Robert', 'Julien'),
(4, 'Durand', 'Sophie'),
(5, 'Bernard', 'Lucas'),
(6, 'Hassan', 'Nadia'),
(7, 'Martin', 'Claire'),
(8, 'Diallo', 'Moussa'),
(9, 'Robert', 'Julien'),
(10, 'Durand', 'Sophie'),
(11, 'Bernard', 'Lucas'),
(12, 'Hassan', 'Nadia');


CREATE TABLE `categorie` (
  `id_categorie` int(11) NOT NULL,
  `libelle` varchar(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `categorie` (`id_categorie`, `libelle`) VALUES
(5, 'Cybersécurité'),
(1, 'Informatique'),
(2, 'Réseaux'),
(3, 'Roman'),
(4, 'Science');


CREATE TABLE `emprunt` (
  `id_emprunt` int(11) NOT NULL,
  `id_adherent` int(11) NOT NULL,
  `id_livre` int(11) NOT NULL,
  `date_emprunt` date NOT NULL,
  `date_retour_prevue` date NOT NULL,
  `date_retour` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `emprunt` (`id_emprunt`, `id_adherent`, `id_livre`, `date_emprunt`, `date_retour_prevue`, `date_retour`) VALUES
(1, 1, 1, '2026-09-21', '2026-10-05', NULL),
(2, 4, 4, '2026-09-21', '2026-10-05', NULL);



CREATE TABLE `livre` (
  `id_livre` int(11) NOT NULL,
  `titre` varchar(200) NOT NULL,
  `isbn` varchar(20) NOT NULL,
  `annee_publication` int(11) DEFAULT NULL,
  `disponible` tinyint(1) NOT NULL DEFAULT 1,
  `id_categorie` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `livre` (`id_livre`, `titre`, `isbn`, `annee_publication`, `disponible`, `id_categorie`) VALUES
(1, 'Comprendre SQL', '9780000000001', 2025, 0, 1),
(2, 'Administration Linux', '9780000000002', 2024, 1, 1),
(3, 'Fondamentaux des réseaux', '9780000000003', 2025, 0, 2),
(4, 'Routage et commutation', '9780000000004', 2023, 0, 2),
(5, 'La ville silencieuse', '9780000000005', 2022, 1, 3),
(6, 'Introduction aux sciences', '9780000000006', 2021, 1, 4),
(7, 'Sécurité des applications Web', '9780000000007', 2026, 0, 5),
(8, 'Python pour débutants', '9780000000008', 2026, 1, 1),
(9, 'Les chemins du désert', '9780000000009', 2020, 1, 3),
(10, 'Pentest : principes et méthodes', '9780000000010', 2025, 1, 5);


CREATE TABLE `livre_auteur` (
  `id_livre` int(11) NOT NULL,
  `id_auteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;



INSERT INTO `livre_auteur` (`id_livre`, `id_auteur`) VALUES
(1, 1),
(1, 2),
(2, 3),
(3, 2),
(4, 2),
(4, 3),
(5, 4),
(6, 5),
(7, 3),
(7, 6),
(8, 1),
(9, 4),
(10, 6);


ALTER TABLE `adherent`
  ADD PRIMARY KEY (`id_adherent`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `auteur`
  ADD PRIMARY KEY (`id_auteur`);


ALTER TABLE `categorie`
  ADD PRIMARY KEY (`id_categorie`),
  ADD UNIQUE KEY `libelle` (`libelle`);


ALTER TABLE `emprunt`
  ADD PRIMARY KEY (`id_emprunt`),
  ADD KEY `id_adherent` (`id_adherent`),
  ADD KEY `id_livre` (`id_livre`);


ALTER TABLE `livre`
  ADD PRIMARY KEY (`id_livre`),
  ADD UNIQUE KEY `isbn` (`isbn`),
  ADD KEY `fk_livre_categorie` (`id_categorie`);


ALTER TABLE `livre_auteur`
  ADD PRIMARY KEY (`id_livre`,`id_auteur`),
  ADD KEY `id_auteur` (`id_auteur`);


ALTER TABLE `adherent`
  MODIFY `id_adherent` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;


ALTER TABLE `auteur`
  MODIFY `id_auteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;


ALTER TABLE `categorie`
  MODIFY `id_categorie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;


ALTER TABLE `emprunt`
  MODIFY `id_emprunt` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


ALTER TABLE `livre`
  MODIFY `id_livre` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

Contraintes pour la table `emprunt`

ALTER TABLE `emprunt`
  ADD CONSTRAINT `emprunt_ibfk_1` FOREIGN KEY (`id_adherent`) REFERENCES `adherent` (`id_adherent`),
  ADD CONSTRAINT `emprunt_ibfk_2` FOREIGN KEY (`id_livre`) REFERENCES `livre` (`id_livre`);


ALTER TABLE `livre`
  ADD CONSTRAINT `fk_livre_categorie` FOREIGN KEY (`id_categorie`) REFERENCES `categorie` (`id_categorie`);


ALTER TABLE `livre_auteur`
  ADD CONSTRAINT `livre_auteur_ibfk_1` FOREIGN KEY (`id_livre`) REFERENCES `livre` (`id_livre`),
  ADD CONSTRAINT `livre_auteur_ibfk_2` FOREIGN KEY (`id_auteur`) REFERENCES `auteur` (`id_auteur`);
COMMIT;

