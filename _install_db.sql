

CREATE TABLE :Pelaaja
(
   id SMALLINT NOT NULL AUTO_INCREMENT,   
   nimi VARCHAR(100),
   PRIMARY KEY(player_id)
);
SHOW WARNINGS;

CREATE TABLE :Kiessi
(
    id SMALLINT NOT NULL AUTO_INCREMENT,
    pelaaja INT,
    rata INT,
    tulos INT,
    PRIMARY KEY (id)
);
SHOW WARNINGS;


CREATE TABLE :Kisa
(
    id SMALLINT NOT NULL AUTO_INCREMENT,
    rata INT,
    aika TIMESTAMP,
    PRIMARY KEY (id)
);

CREATE TABLE :Tasoitus_taulu
(
    id SMALLINT NOT NULL AUTO_INCREMENT,
    pelaaja INT,
    tasoitus  DOUBLE,
    FOREIGN KEY (pelaaja) REFERENCES :Pelaaja(id),
    PRIMARY KEY (id)    
);
SHOW WARNINGS;

CREATE TABLE :Rata
(
    id SMALLINT NOT NULL AUTO_INCREMENT,
    nimi VARCHAR(100),
    course_rating INT,
    slope_rating INT,
    PRIMARY KEY (id)
);
SHOW WARNINGS;
