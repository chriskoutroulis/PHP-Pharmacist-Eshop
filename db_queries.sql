CREATE DATABASE IF NOT EXISTS medical DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;

GRANT ALL PRIVILEGES ON medical.* TO 'mdadmin'@'localhost' IDENTIFIED BY '45>89pharm';

USE medical;

CREATE TABLE IF NOT EXISTS pharmacists (
email VARCHAR(50) NOT NULL PRIMARY KEY,
password VARCHAR(50) NOT NULL,
full_name VARCHAR(70) NOT NULL,
afm VARCHAR(20) NOT NULL,
telephone CHAR(10) NOT NULL,
address VARCHAR(150) NOT NULL
);

INSERT INTO pharmacists VALUES ('chriskoutroulis@gmail.com', '12345', 'Χριστόδουλος Κουτρουλής', '131876435', '2104653122', 'Οδός Σινώπης 32, Κάτω Λιτόχωρο');
INSERT INTO pharmacists VALUES ('pharmakeros@medicine.gr', '54321', 'Ανδρέας Ψυχογυιός', '130654988', '28210-3210985', 'Οδός Αναστασιάδου 122, Κουνουπιδιανά - Χανιά');
INSERT INTO pharmacists VALUES ('gazie43@gmail.com', 'fh674', 'Ιωάννα Παπουτσή', '150017895', '2115764823', 'Οδός Πατησίων 57, Αθήνα');
INSERT INTO pharmacists VALUES ('meftyhiou@hotmail.com', 'gt732', 'Μαρία Ευθυμίου', '178395271', '2106427598', 'Οδός Αιτωλίας 15, Αμπελόκηποι');
INSERT INTO pharmacists VALUES ('farmakeio-ioannou@yahoo.com', '65381', 'Γεώργιος Ιωάννου', '149600732', '2108076327', 'Οδός Ανοίξεως 21, Κηφισιά');


CREATE TABLE IF NOT EXISTS medications (
product_code VARCHAR(50) NOT NULL PRIMARY KEY,
product_name VARCHAR(50) NOT NULL,
packet_type VARCHAR(50),
instructions VARCHAR(300),
price DEC(7,2) NOT NULL,
fpa_factor DEC(3,2) DEFAULT 0.06
);

INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Depon', '40 χάπια', 'Δοσολογία: Πάρτε ένα όταν πονάτε. Αν δεν περάσει, πάρτε κι άλλο ένα.', 1.60, 0.06);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Maalox', '20 μασούμενα δισκία', 'Δοσολογία: Πάρτε δύο όταν έχετε παλινδρόμηση.', 2.30, 0.09);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Amoxil 500mg', '20 χάπια', 'Δοσολογία: 1 το πρωί και ένα το βράδυ για μια εβδομάδα.', 5.00, 0.06);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Amoxil 300mg', '20 χάπια', 'Δοσολογία: 1 το πρωί και ένα το βράδυ για μια εβδομάδα.', 4.75, 0.06);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Comtrex Cold', '30 χάπια', 'Δοσολογία: 2 χάπια ανά 8 ώρες για όσο επιμένουν τα συμπτώματα', 5.50, 0.13);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Doralin', '20 χάπια', 'Δοσολογία: 1 δισκίο ανά 8 ώρες. Επαναλάβετε για τουλάχιστον 10 ημέρες.', 12.00, 0.23);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Primperan', '20 δισκία', 'Δοσολογία: Πάρτε 1 ανά 10kg σωματικού βάρους. ', 2.30, 0.09);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Daktarin', 'κρέμα 30g', 'Δοσολογία: Επάλειψη πρωί-βράδυ. ', 3.00, 0.13);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Niflamol', '20 καψάκια', 'Δοσολογία: 1 δισκίο κάθε 8 ώρες.', 2.50, 0.06);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Nystamysyn', 'πόσιμο εναιώρημα 50ml', 'Δοσολογία: Πλύσεις στόματος 3 φορές την ημέρα.', 5.00, 0.06);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Nasosyn', 'ρινικό spray 10ml', 'Δοσολογία: κάθε 12 ώρες, 2 ψεκασμοί στο κάθε ρουθούνι.', 3.80, 0.23);
INSERT INTO medications VALUES (CONCAT('ph', UUID_SHORT()), 'Fucidin', 'κρέμα 15g', 'Δοσολογία: Επάλειψη πρωί-βράδυ.', 2.99, 0.13);
