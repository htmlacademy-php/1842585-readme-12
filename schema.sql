CREATE
DATABASE readme
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;

USE
readme;

CREATE TABLE users
(
  id         INT AUTO_INCREMENT PRIMARY KEY,
  registered DATETIME NOT NULL,
  email      VARCHAR(128) NOT NULL,
  login      VARCHAR(128) NOT NULL,
  password   CHAR(64)     NOT NULL,
  avatar     VARCHAR(125)
);

CREATE TABLE content_types
(
  id         INT AUTO_INCREMENT PRIMARY KEY,
  name       VARCHAR(128) NOT NULL,
  icon_class CHAR(32)
);

CREATE TABLE hashtags
(
  id   INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(128) NOT NULL
);

CREATE TABLE posts
(
  id          INT AUTO_INCREMENT PRIMARY KEY,
  created     DATETIME NOT NULL,
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

CREATE TABLE post_hashtags
(
  id         INT AUTO_INCREMENT PRIMARY KEY,
  post_id    INT NOT NULL,
  hashtag_id INT NOT NULL,
  FOREIGN KEY (post_id) REFERENCES posts (id),
  FOREIGN KEY (hashtag_id) REFERENCES hashtags (id)
);

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

CREATE TABLE likes
(
  id      INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  post_id INT NOT NULL,
  FOREIGN KEY (user_id) REFERENCES users (id),
  FOREIGN KEY (post_id) REFERENCES posts (id)
);

CREATE TABLE subscribes
(
  id           INT AUTO_INCREMENT PRIMARY KEY,
  author_id    INT NOT NULL,
  subscribe_id INT NOT NULL,
  FOREIGN KEY (author_id) REFERENCES users (id),
  FOREIGN KEY (subscribe_id) REFERENCES users (id)
);

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
