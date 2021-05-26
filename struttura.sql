-- Creazione database

DROP DATABASE IF EXISTS casafarm_db;
CREATE DATABASE casafarm_db;
use casafarm_db;

CREATE TABLE Sedi (
	ID INTEGER AUTO_INCREMENT PRIMARY KEY,
    Indirizzo VARCHAR(64),
    Citta VARCHAR(64),
    
    UNIQUE(Indirizzo, Citta)
) Engine = 'InnoDB';

CREATE TABLE Teams (
	ID INTEGER AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(64) UNIQUE,
    Sede INTEGER NOT NULL,
    Leader INTEGER,
    
    INDEX idx_sede(Sede),
    FOREIGN KEY(Sede) REFERENCES Sedi(ID),
    INDEX idx_leader(Leader)
) Engine = 'InnoDB';

CREATE TABLE Dipendenti (
	ID INTEGER AUTO_INCREMENT PRIMARY KEY,
    Username VARCHAR(64) UNIQUE,
    Password VARCHAR(255),
	Email VARCHAR(64),
    Cognome VARCHAR(64),
    Nome VARCHAR(64),
    Stipendio INTEGER DEFAULT '1400',
    Data_Assunzione DATE,
    Anni_Servizio INTEGER,
    Team INTEGER NOT NULL,
    
    INDEX idx_team(Team),
    FOREIGN KEY(Team) REFERENCES Teams(ID)
) Engine = 'InnoDB';

ALTER TABLE Teams ADD FOREIGN KEY(Leader) REFERENCES Dipendenti(ID);

CREATE TABLE Certificati (
	ID INTEGER AUTO_INCREMENT PRIMARY KEY,
    Dipendente INTEGER NOT NULL,
    Codice VARCHAR(8),
    Data DATE,
    Scadenza DATE,
    
    UNIQUE(Dipendente, Codice, Data),
    INDEX idx_dipendente(Dipendente),
    FOREIGN KEY(Dipendente) REFERENCES Dipendenti(ID)
) Engine = 'InnoDB';

CREATE TABLE Ricerche (
	Codice INTEGER AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(64),
    Budget INTEGER
) Engine = 'InnoDB';
ALTER TABLE Ricerche AUTO_INCREMENT = 1425;

CREATE TABLE Teams_Ricerca (
	ID INTEGER PRIMARY KEY,
    Ricerca INTEGER,
    Data_Inizio DATE,
    
    INDEX idx_teamR(ID),
    FOREIGN KEY(ID) REFERENCES Teams(ID),
    INDEX idx_ricerca(Ricerca),
    FOREIGN KEY(Ricerca) REFERENCES Ricerche(Codice)
) Engine = 'InnoDB';

CREATE TABLE Ricerche_Terminate (
	Team INTEGER,
    Ricerca INTEGER,
    Data_Inizio DATE,
    Data_Fine DATE,
    
    PRIMARY KEY(Team, Ricerca),
    INDEX idx_team(Team),
    FOREIGN KEY(Team) REFERENCES Teams_Ricerca(ID),
    INDEX idx_ricercaC(Ricerca),
    FOREIGN KEY(Ricerca) REFERENCES Ricerche(Codice)
) Engine = 'InnoDB';

CREATE TABLE Prodotti (
	Codice INTEGER AUTO_INCREMENT PRIMARY KEY,
    Nome VARCHAR(64),
    Prezzo FLOAT,
    Brevetto INTEGER NOT NULL,
	Immagine VARCHAR(255),
	Descrizione TEXT,
    
    INDEX idx_brevetto(Brevetto),
    FOREIGN KEY(Brevetto) REFERENCES Ricerche(Codice)
) Engine = 'InnoDB';
ALTER TABLE Prodotti AUTO_INCREMENT = 20640;

CREATE TABLE Teams_Produzione (
	ID INTEGER PRIMARY KEY,
    Prodotto INTEGER,
    
    INDEX idx_teamP(ID),
    FOREIGN KEY(ID) REFERENCES Teams(ID),
    INDEX idx_prodotto(Prodotto),
    FOREIGN KEY(Prodotto) REFERENCES Prodotti(Codice)
) Engine = 'InnoDB';

CREATE TABLE Lotti (
	Numero INTEGER,
	Prodotto INTEGER,
    Data_Produzione DATE,
    Data_Scadenza DATE,
    Magazzino INTEGER,
    
    PRIMARY KEY(Numero, Prodotto),
    INDEX idx_prodottoL(Prodotto),
    FOREIGN KEY(Prodotto) REFERENCES Prodotti(Codice),
    INDEX idx_magazzino(Magazzino),
    FOREIGN KEY(Magazzino) REFERENCES Sedi(ID)
) Engine = 'InnoDB';

CREATE TABLE Annunci (
	ID INTEGER AUTO_INCREMENT PRIMARY KEY,
	Autore INTEGER,
	Data DATE,
	Messaggio TEXT,
	Immagine VARCHAR(255),

	INDEX idx_autore(Autore),
	FOREIGN KEY(Autore) REFERENCES Dipendenti(ID)
) Engine = 'InnoDB';


-- Allineamento attributo ridondante "Anni_Servizio"
-- Trigger:
DELIMITER //
CREATE TRIGGER setAnniServizio
BEFORE INSERT ON Dipendenti
FOR EACH ROW
BEGIN
    SET NEW.Anni_Servizio = TIMESTAMPDIFF(YEAR, NEW.Data_Assunzione, CURDATE());
END//
DELIMITER ;

-- Procedura periodica:
DELIMITER //
CREATE PROCEDURE updateAnniServizio()
BEGIN
	UPDATE Dipendenti
    SET Anni_Servizio = TIMESTAMPDIFF(YEAR, Data_Assunzione, CURDATE())
    WHERE Matricola <> 0;
END//
DELIMITER ;


-- Operazione di aggiunta lotto
DELIMITER //
CREATE PROCEDURE addInventory(IN Prod INTEGER)
BEGIN
	SET @Numero_Lotto = (
		CASE
			WHEN EXISTS (SELECT * FROM Lotti WHERE Prodotto = Prod) THEN (SELECT MAX(Numero) FROM Lotti WHERE Prodotto = Prod) + 1
            ELSE 1
        END
    );
    INSERT INTO Lotti(Numero, Prodotto, Data_Produzione, Data_Scadenza) VALUES (@Numero_Lotto, Prod, CURDATE(), CURDATE() + INTERVAL 2 YEAR);
END//
DELIMITER ;


-- Popolazione database

INSERT INTO Sedi(Indirizzo, Citta) VALUES
	("Via Roma, 1", "Catania"),
	("Via Cagliari, 10", "Catania"),
	("Via Torino, 23", "Roma"),
	("Via Milano, 36", "Napoli"),
	("Via Venezia, 14", "Milano");

INSERT INTO Teams(Nome, Sede) VALUES
	("Team Ricerca A", 1),
	("Team Ricerca B", 1),
	("Team Ricerca C", 1),
	("Team Ricerca D", 1),
	("Team Produzione A", 2),
	("Team Produzione B", 3),
	("Team Produzione C", 4),
	("Team Produzione D", 5),
	("Team Produzione E", 5);

INSERT INTO Dipendenti(Username, Password, Email, Cognome, Nome, Stipendio, Data_Assunzione, Team) VALUES
	("mnunzio", "", 'email@gmail.com', "Mazzanti", "Nunzio", 1800, "2000-03-10", 1),
	("pezno", "", 'email@gmail.com', "Pirozzi", "Enzo", 1800, "2006-06-23", 2),
	("cnatalino", "", 'email@gmail.com', "Cattaneo", "Natalino", 1800, "2010-02-28", 3),
	("tgino", "", 'email@gmail.com', "Trentino", "Gino", 1800, "2002-11-02", 4),
	("mstefania", "", 'email@gmail.com', "Mancini", "Stefania", 1800, "2005-05-12", 5),
	("biacopo", "", 'email@gmail.com', "Bellucci", "Iacopo", 1800, "2015-08-22", 6),
	("tanastasia", "", 'email@gmail.com', "Trevisani", "Anastasia", 1800, "2004-02-07", 7),
	("bivano", "", 'email@gmail.com', "Barese", "Ivano", 1800, "2011-02-01", 8),
	("awilma", "", 'email@gmail.com', "Arcuri", "Wilma", 1800, "2013-10-22", 9);

UPDATE Teams SET Leader = 1 WHERE ID = 1;
UPDATE Teams SET Leader = 2 WHERE ID = 2;
UPDATE Teams SET Leader = 3 WHERE ID = 3;
UPDATE Teams SET Leader = 4 WHERE ID = 4;
UPDATE Teams SET Leader = 5 WHERE ID = 5;
UPDATE Teams SET Leader = 6 WHERE ID = 6;
UPDATE Teams SET Leader = 7 WHERE ID = 7;
UPDATE Teams SET Leader = 8 WHERE ID = 8;
UPDATE Teams SET Leader = 9 WHERE ID = 9;

INSERT INTO Dipendenti(Username, Password, Email, Cognome, Nome, Data_Assunzione, Team) VALUES
	("mmanuele", "", 'email@gmail.com', "Mancini", "Manuele", "2008-12-14", 2),
	("lloredana", "", 'email@gmail.com', "Lucchesi", "Loredana", "2014-05-04", 3),
	("mmanfredo", "", 'email@gmail.com', "Manna", "Manfredo", "2009-09-29", 4),
	("talessandro", "", 'email@gmail.com', "Toscano", "Alessandro", "2017-03-02", 5),
	("fnovella", "", 'email@gmail.com', "Fiorentini", "Novella", "2020-02-25", 6),
	("relvio", "", 'email@gmail.com', "Rossi", "Elvio", "2020-06-23", 7),
	("nmarco", "", 'email@gmail.com', "Napolitani", "Marco", "2012-07-24", 8),
	("tsabrina", "", 'email@gmail.com', "Trentini", "Sabrina", "2018-01-14", 1),
	("rrolando", "", 'email@gmail.com', "Rizzo", "Rolando", "2014-03-18", 2),
	("bdino", "", 'email@gmail.com', "Barese", "Dino", "2019-11-21", 3),
	("cmara", "", 'email@gmail.com', "Colombo", "Mara", "2016-07-19", 4),
	("cfulgenzia", "", 'email@gmail.com', "Costa", "Fulgenzia", "2020-12-21", 5),
	("fmarco", "", 'email@gmail.com', "Fiorentino", "Marco", "2017-09-04", 6),
	("gurania", "", 'email@gmail.com', "Genovese", "Urania", "2018-04-13", 7),
	("echerubino", "", 'email@gmail.com', "Esposito", "Cherubino", "2016-10-08", 8);

INSERT INTO Certificati(Dipendente, Codice, Data, Scadenza) VALUES
	(2, "BLS", "2010-02-01", "2020-02-01"),
	(2, "BLS", "2020-02-16", "2030-02-16"),
	(7, "BLS", "2016-04-22", "2026-04-22"),
	(12, "BLS", "2009-10-18", "2019-10-18"),
	(5, "BLS", "2018-04-12", "2028-04-12"),
	(5, "CPI", "2014-10-03", "2019-10-03"),
	(5, "CPI", "2019-10-20", "2024-10-20"),
	(7, "CPI", "2017-06-05", "2022-06-05"),
	(18, "CPI", "2020-01-18", "2025-01-18"),
	(22, "CPI", "2013-12-11", "2018-12-11"),
	(10, "BLS", "2019-02-19", "2029-02-19");

INSERT INTO Ricerche(Nome, Budget) VALUES
	("Studio Antinfiammatorio 2015", 85000), 			-- 1425
	("Vaccino COVID-19 2020", 250000),					-- 1426
	("Studio Reflussi e Gastroprotettori 2015", 45000),	-- 1427
	("Ricerca Farmaci Antinausea 2019", 35000),			-- 1428
	("Microrganismi Antibiotici 2020", 125000),			-- 1429
	("Ricerca Azione Antistaminica 2018", 98000);		-- 1430

INSERT INTO Teams_Ricerca(ID, Ricerca, Data_Inizio) VALUES
	(1, 1428, "2019-04-12"),
	(2, 1429, "2020-12-16"),
	(3, 1429, "2020-12-16"),
	(4, 1430, "2018-04-22");

INSERT INTO Ricerche_Terminate(Team, Ricerca, Data_Inizio, Data_Fine) VALUES
	(1, 1425, "2015-08-24", "2019-04-11"),
	(2, 1426, "2020-02-10", "2020-12-15"),
	(3, 1426, "2020-02-10", "2020-12-15"),
	(3, 1425, "2015-08-26", "2019-04-11"),
	(4, 1427, "2015-05-02", "2018-04-20");

INSERT INTO Prodotti(Nome, Prezzo, Brevetto, Immagine, Descrizione) VALUES
	("Hockey 80mg", 4.10, 1425, "img/hockey.png", "HOCKEy fa parte della categoria degli antiinfiammatori non steroidei.
		Trattamento sintomatico e di breve durata di stati infiammatori associati a dolore quali quelli a carico dell'apparato osteoarticolare, dolore post operatorio e otiti."),		-- 20640
	("Minut 200mg", 4.95, 1425, "img/minut.png", "Minut si usa nel trattamento di dolori di varia origine e natura. È un coadiuvante nel trattamento sintomatico degli stati febbrili e influenzali.
		Contiene lo stesso principio attivo del noto \"Moment\", ma è un po' più lento a fare effetto."),																				-- 20641
	("Covid-19 Vaccine", 15, 1426, "img/vaccine.png", "Il nostro vaccino contro il SARS-CoV-2 è stato rigorosamente testato e approvato dall'Agenzia Europea del Farmaco (EMA) e dalla Food and Drug Administration (FDA) statunitense.
		Può contenere tracce di: 5G, Wi-Fi, Bluetooth, NFC, AM/FM/CQCQ."),																												-- 20642
	("Maaloz Dilusso 20mg", 6.95, 1426, "img/maaloz.png", "Maaloz Dilusso è usato per il trattamento a breve termine dei sintomi da reflusso (ad esempio bruciore di stomaco, rigurgito acido) negli adulti.
		Contiene lo stesso principio attivo del noto \"Maalox Reflusso\", ma a un prezzo più elevato.");																				-- 20643

INSERT INTO Teams_Produzione(ID, Prodotto) VALUES
	(5, 20640),
	(6, 20641),
	(7, 20642),
	(8, 20642),
	(9, 20643);

INSERT INTO Lotti(Numero, Prodotto, Data_Produzione, Data_Scadenza, Magazzino) VALUES
	(1, 20640, "2020-01-10", "2022-01-10", 2),
	(2, 20640, "2020-02-13", "2022-02-13", 3),
	(3, 20640, "2020-03-11", "2022-03-11", 3),
	(1, 20641, "2020-02-03", "2022-02-03", 2),
	(2, 20641, "2020-04-11", "2022-04-11", 2),
	(3, 20641, "2020-05-25", "2022-05-25", 2),
	(4, 20641, "2020-07-21", "2022-07-21", 4),
	(1, 20642, "2020-12-16", "2021-12-16", 2),
	(2, 20642, "2020-12-18", "2021-12-18", 3),
	(3, 20642, "2020-12-20", "2021-12-20", 4),
	(4, 20642, "2020-12-22", "2021-12-22", 4),
	(5, 20642, "2020-12-27", "2021-12-27", 5),
	(1, 20643, "2021-02-12", "2023-02-12", 2);

INSERT INTO Annunci(Autore, Data, Messaggio, Immagine) VALUES
	(1, "2021-05-12", "A partire da Lunedì 17 Maggio 2021, il Team Ricerca A sarà temporaneamente in modalità smart-working. Per qualsiasi domanda di carattere tecnico siete pregati di contattare il responsabile.", "https://images.unsplash.com/1/work-stations-plus-espresso.jpg?crop=entropy&cs=tinysrgb&fit=max&fm=jpg&ixid=MnwyMzM5NjV8MHwxfHNlYXJjaHw5fHxzbWFydCUyMHdvcmtpbmd8ZW58MHwwfHx8MTYyMjA0MTY1Ng&ixlib=rb-1.2.1&q=80&w=400"),
	(5, "2021-05-14", "Durante la settimana 17-21 Maggio 2021, la produzione di Hockey 80mg sarà sospesa.", "");