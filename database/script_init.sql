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
    CONSTRAINT PK_users PRIMARY KEY (`user_id`)
);
ALTER TABLE users
    ADD CONSTRAINT FK_users_users_types FOREIGN KEY (user_type_id) REFERENCES users_types (user_type_id);

CREATE TABLE tracks
(
    `track_id`       int          NOT NULL AUTO_INCREMENT,
    `name`           varchar(255) NOT NULL,
    `departement`    varchar(3)   NOT NULL,
    `estimated_time` int          NOT NULL,
    `level`          int          NOT NULL,
    `description`    varchar(512) NOT NULL,
    `date_start`     datetime,
    `date_end`       datetime,
    `user_id`        int          NOT NULL,
    CONSTRAINT PK_tracks PRIMARY KEY (`track_id`)
);
ALTER TABLE tracks
    ADD CONSTRAINT FK_tracks_users FOREIGN KEY (user_id) REFERENCES users (user_id);

CREATE TABLE enigmas
(
    `enigma_id`      int          NOT NULL AUTO_INCREMENT,
    `name`           varchar(255) NOT NULL,
    `image_url`      varchar(255),
    `question`       varchar(255) NOT NULL,
    `answer`         varchar(255) NOT NULL,
    `difficulty`     varchar(255) NOT NULL,
    `estimated_time` int          NOT NULL,
    `track_id`       int          NOT NULL,
    CONSTRAINT PK_enigmas PRIMARY KEY (`enigma_id`)
);
ALTER TABLE enigmas
    ADD CONSTRAINT FK_enigmas_tracks FOREIGN KEY (track_id) REFERENCES tracks (track_id);

CREATE TABLE enigmas_answers
(
    `enigma_answer_id` int          NOT NULL AUTO_INCREMENT,
    `answer`           varchar(255) NOT NULL,
    `date_anwser`      datetime     NOT NULL,
    `nb_indice_used`   int          NOT NULL,
    `enigma_id`        int          NOT NULL,
    `user_id`          int          NOT NULL,
    CONSTRAINT PK_enigmas_answers PRIMARY KEY (`enigma_answer_id`)
);
ALTER TABLE enigmas_answers
    ADD CONSTRAINT FK_enigmas_answers_enigmas FOREIGN KEY (enigma_id) REFERENCES enigmas (enigma_id);
ALTER TABLE enigmas_answers
    ADD CONSTRAINT FK_enigmas_answers_users FOREIGN KEY (user_id) REFERENCES users (user_id);

CREATE TABLE indices
(
    `indice_id`   int NOT NULL AUTO_INCREMENT,
    `description` varchar(255),
    `image_url`   varchar(255),
    `order`       int NOT NULL,
    `enigma_id`   int NOT NULL,
    CONSTRAINT PK_indices PRIMARY KEY (`indice_id`)
);
ALTER TABLE indices
    ADD CONSTRAINT FK_indices_enigmas FOREIGN KEY (enigma_id) REFERENCES enigmas (enigma_id);

INSERT INTO users_types (user_type_id, name)
VALUES (1, 'Joueur'),
       (2, 'Créateur');

INSERT INTO users (pseudo, email, password, user_type_id, departement)
VALUES ('Joueur', 'test@gmail.com', 'test', 1, '83'),
       ('Créateur', 'test@gmail.com', 'test', 2, '83');