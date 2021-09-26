DROP
DATABASE readme;

CREATE
DATABASE readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE
readme;

CREATE TABLE users
(
  id         INT AUTO_INCREMENT PRIMARY KEY,
  registered DATETIME            NOT NULL,
  email      VARCHAR(128) UNIQUE NOT NULL,
  login      VARCHAR(128)        NOT NULL,
  password   VARCHAR(64)         NOT NULL,
  avatar     VARCHAR(125)
);

CREATE TABLE content_types
(
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(128) UNIQUE NOT NULL,
  icon_class VARCHAR(32)
);

CREATE TABLE hashtags
(
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL
);

CREATE TABLE posts
(
  id          INT AUTO_INCREMENT PRIMARY KEY,
  created     DATETIME     NOT NULL,
  title       VARCHAR(200) NOT NULL,
  content     VARCHAR(1000),
  author      VARCHAR(128),
  picture     VARCHAR(125),
  video       VARCHAR(125),
  website     VARCHAR(125),
  views_count INT DEFAULT 0,
  user_id     INT          NOT NULL,
  type_id     INT          NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (type_id) REFERENCES content_types (id)
);
/*
  Внешний ключ user_id нужен для контроля целостности данных, чтобы нельзя было удалить пользователя, если у него есть посты.
  Внешний ключ type_id нужен для контроля целостности данных, чтобы нельзя было удалить тип контента, если у него есть посты.
*/

CREATE TABLE post_hashtags
(
  id         INT AUTO_INCREMENT PRIMARY KEY,
  post_id    INT NOT NULL,
  hashtag_id INT NOT NULL,
  FOREIGN KEY (post_id) REFERENCES posts (id),
  FOREIGN KEY (hashtag_id) REFERENCES hashtags (id)
);
/*
  Внешний ключ post_id нужен для контроля целостности данных, чтобы нельзя было удалить пост, если у него есть hashtag.
  Если есть необходимость удалить пост, то предварительно стоит удалить все записи, которые ссылаются на данный пост в таблице post_hashtags.
  Внешний ключ hashtag_id нужен для контроля целостности данных, чтобы нельзя было удалить хэштег, если он закреплен к посту.
  Если есть необходимость удалить хэштег, то предварительно стоит удалить все записи, которые ссылаются на данный хэштег в таблице post_hashtags.
*/

CREATE TABLE post_comments
(
  id      INT AUTO_INCREMENT PRIMARY KEY,
  created DATETIME     NOT NULL,
  content VARCHAR(300) NOT NULL,
  user_id INT          NOT NULL,
  post_id INT          NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (post_id) REFERENCES posts (id)
);
/*
  Внешний ключ user_id нужен для контроля целостности данных, чтобы нельзя было удалить пользователя, если он создавал комментарий.
  Если есть необходимость удалить пользователя, то предварительно стоит удалить все записи, которые ссылаются на данного пользователя в таблице post_comments.
  Внешний ключ post_id нужен для контроля целостности данных, чтобы нельзя было удалить пост, если у него есть комментарий.
  Если есть необходимость удалить пост, то предварительно стоит удалить все записи, которые ссылаются на данный пост в таблице post_comments.
*/

CREATE TABLE likes
(
  id      INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  post_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (post_id) REFERENCES posts (id)
);
/*
  Внешний ключ user_id нужен для контроля целостности данных, чтобы нельзя было удалить пользователя, если он ставил лайки.
  Если есть необходимость удалить пользователя, то предварительно стоит удалить все записи, которые ссылаются на данного пользователя в таблице likes.
  Внешний ключ post_id нужен для контроля целостности данных, чтобы нельзя было удалить пост, если у него есть лайки.
  Если есть необходимость удалить пост, то предварительно стоит удалить все записи, которые ссылаются на данный пост в таблице likes.
*/

CREATE TABLE subscribes
(
  id           INT AUTO_INCREMENT PRIMARY KEY,
  author_id    INT NOT NULL,
  subscribe_id INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES users (id),
  FOREIGN KEY (subscribe_id) REFERENCES users (id)
);
/*
  Внешний ключ author_id нужен для контроля целостности данных, чтобы нельзя было удалить пользователя, если на него подписаны.
  Если есть необходимость удалить пользователя, то предварительно стоит удалить все записи, которые ссылаются на данного пользователя в таблице subscribes.
  Внешний ключ subscribe_id нужен для контроля целостности данных, чтобы нельзя было удалить пользователя, если он подписался на кого-то.
  Если есть необходимость удалить пользователя, то предварительно стоит удалить все записи, которые ссылаются на данного пользователя в таблице subscribes.
*/

CREATE TABLE messages
(
  id           INT AUTO_INCREMENT PRIMARY KEY,
  created      DATETIME      NOT NULL,
  content      VARCHAR(1000) NOT NULL,
  user_id      INT           NOT NULL,
  recipient_id INT           NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (recipient_id) REFERENCES users (id)
);
/*
  Внешний ключ user_id нужен для контроля целостности данных, чтобы нельзя было удалить пользователя, если он писал сообщения.
  Если есть необходимость удалить пользователя, то предварительно стоит удалить все записи, которые ссылаются на данного пользователя в таблице messages.
  Внешний ключ recipient_id нужен для контроля целостности данных, чтобы нельзя было удалить пользователя, если ему отправляли сообщение.
  Если есть необходимость удалить пользователя, то предварительно стоит удалить все записи, которые ссылаются на данного пользователя в таблице messages.
*/
