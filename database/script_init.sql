DROP DATABASE IF EXISTS ceresia_adventure;
CREATE DATABASE ceresia_adventure CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

use ceresia_adventure;

CREATE TABLE users_types
(
    `user_type_id` int          NOT NULL,
    `name`         varchar(255) NOT NULL,
    CONSTRAINT PK_users_types PRIMARY KEY (`user_type_id`)
);

CREATE TABLE users
(
    `user_id`      int          NOT NULL AUTO_INCREMENT,
    `pseudo`       varchar(255) not null,
    `email`        varchar(255) not null,
    `password`     varchar(255) not null,
    `user_type_id` int          not null,
    `departement`  varchar(3)   not null,
    CONSTRAINT PK_users PRIMARY KEY (`user_id`),
    UNIQUE KEY `email` (`email`) USING HASH,

);
ALTER TABLE users
    ADD CONSTRAINT FK_users_users_types FOREIGN KEY (user_type_id) REFERENCES users_types (user_type_id);


CREATE TABLE trails
(
    `trail_id`       int          NOT NULL AUTO_INCREMENT,
    `name`           varchar(255) NOT NULL,
    `departement`    varchar(3)   NOT NULL,
    `estimated_time` int          NOT NULL,
    `level`          int          NOT NULL,
    `description`    varchar(512) NOT NULL,
    `visible`        BOOLEAN DEFAULT true NOT NULL,
    `date_start`     datetime,
    `date_end`       datetime,
    `user_id`        int          NOT NULL,
    CONSTRAINT PK_trails PRIMARY KEY (`trail_id`)
);
ALTER TABLE trails
    ADD CONSTRAINT FK_trails_users FOREIGN KEY (user_id) REFERENCES users (user_id);

CREATE TABLE enigmas
(
    `enigma_id`      int          NOT NULL AUTO_INCREMENT,
    `name`           varchar(255) NOT NULL,
    `question`       varchar(255) NOT NULL,
    `answer`         varchar(255) NOT NULL,
    `difficulty`     varchar(255) NOT NULL,
    `estimated_time` int          NOT NULL,
    `trail_id`       int          NOT NULL,
    `hint`           VARCHAR(255) NOT NULL,
    CONSTRAINT PK_enigmas PRIMARY KEY (`enigma_id`)
);
ALTER TABLE enigmas
    ADD CONSTRAINT FK_enigmas_trails FOREIGN KEY (trail_id) REFERENCES trails (trail_id) on delete CASCADE;

CREATE TABLE ratings
(
    `rating_id`   int NOT NULL AUTO_INCREMENT,
    `rating` int,
    `trail_id` int,
    `user_id` int,
    CONSTRAINT PK_indices PRIMARY KEY (`rating_id`),
    FOREIGN KEY (trail_id) REFERENCES trails (trail_id),
    FOREIGN KEY (user_id) REFERENCES users (user_id)
);

INSERT INTO users_types (user_type_id, name)
VALUES (1, 'Joueur'),
       (2, 'Créateur'),
       (3, 'Admin');

INSERT INTO users (pseudo, email, password, user_type_id, departement)
VALUES ('Joueur', 'joueur@gmail.com', '$2y$10$OoySlgoEp5nlx85/OmIrXehgghiyrITv29IUBzpEuB1E6sgztEeLW', 1, '83'),
       ('Créateur', 'createur@gmail.com', '$2y$10$aj.d9hHkK.ImLOCH9Ixi6Og5C4xXNLKbWQhmWukjmqQVuPmy/WxUi', 2, '83'),
       ('Admin', 'admin@gmail.com', '$2y$10$YimIdIK.OKKYVfBlHDmfyuUJcx.6VuDQ6eu/b5kvM3CKLhZOPM4tS', 3, '83');
