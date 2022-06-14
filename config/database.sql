
DROP TABLE IF EXISTS annoncements_images;
DROP TABLE IF EXISTS images;
DROP TABLE IF EXISTS annoncements;
DROP TABLE IF EXISTS users;
DROP TABLE IF EXISTS admins;
DROP TABLE IF EXISTS visitors;


CREATE TABLE admins(
                       username varchar(50) NOT NULL UNIQUE,
                       password varchar(256) NOT NULL
)ENGINE=INNODB;

CREATE TABLE users (
                       id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
                       name VARCHAR(50),
                       first_name VARCHAR(50),
                       username VARCHAR(50),
                       mail VARCHAR(150) NOT NULL UNIQUE ,
                       date_at DATETIME,
                       password TEXT
)ENGINE=INNODB;

CREATE TABLE annoncements (
                              id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
                              title VARCHAR(50) NOT NULL,
                              description TEXT,
                              zip VARCHAR(5),
                              price INTEGER,
                              city varchar(50),
                              date_at DATETIME,
                              date DATETIME,
                              category INTEGER,
                              type INTEGER,
                              floor INTEGER,
                              surface INTEGER,
                              room INTEGER,
                              user_id INTEGER SIGNED NOT NULL,
                              CONSTRAINT FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=INNODB;
CREATE TABLE images (
                        id INTEGER PRIMARY KEY AUTO_INCREMENT NOT NULL,
                        name VARCHAR(100),
                        path VARCHAR(150)
)ENGINE = INNODB;

CREATE TABLE annoncements_images(
                                    annoncement_id INTEGER NOT NULL ,
                                    image_id INTEGER NOT NULL ,
                                    FOREIGN KEY (annoncement_id) REFERENCES annoncements(id) ON DELETE CASCADE,
                                    FOREIGN KEY (image_id) REFERENCES images(id) ON DELETE CASCADE

)ENGINE = INNODB;



CREATE TABLE visitors (
                          ip varchar(50) NOT NULL UNIQUE,
                          date DATE
)ENGINE=INNODB;