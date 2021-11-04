USE
readme;

-- Добавление типов постов
INSERT INTO content_types (name, icon_class)
VALUES ("Цитата", "quote"),
       ("Ссылка", "link"),
       ("Картинка", "photo"),
       ("Видео", "video"),
       ("Текст", "text");

-- Добавление тестовых пользователей
INSERT INTO users (registered_at, email, login, password, avatar_path)
VALUES ("2015-05-23T14:25:10", "test@test.com", "Лариса", MD5("123456"), "img/userpic-larisa-small.jpg"),
       ("2018-08-21T14:00:56", "test1@test.com", "Виктор", MD5("321456"), "img/userpic-mark.jpg"),
       ("2021-02-14T22:38:18", "test2@test.com", "Владик", MD5("654321"), "img/userpic.jpg");

-- Добавление постов
INSERT INTO posts (created_at, title, content, author, user_id, type_id, views_count)
VALUES ("2021-02-25T22:38:18", "Цитата", "Мы в жизни любим только раз, а после ищем лишь похожих", "Лариса", 1, 1, 18),
       ("2021-04-25T22:38:18", "Игра престолов",
        "Американский драматический сериал в жанре фэнтези, является адаптацией цикла романов «Песнь Льда и Пламени». Создатели сериала — сценаристы Дэвид Беньофф и Дэн Вайс, съёмки ведутся в Северной Ирландии, Хорватии, Исландии, Испании, Марокко и на Мальте. Всего сериал включает 73 серии, объединённые в 8 сезонов. Премьера первого сезона в США состоялась на канале HBO 17 апреля 2011 года. Сериал отмечен множеством наград. Способствовал популяризации Джорджа Мартина как писателя и придуманного им мира.",
        "Владик", 3, 5, 45),
       ("2021-06-15T22:38:18", "Игра престолов", "Не могу дождаться начала финального сезона своего любимого сериала!",
        "Владик", 3, 5, 2),
       ("2021-07-15T22:38:18", "Наконец, обработал фотки!", "img/rock-medium.jpg", "Виктор", 2, 3, 139),
       ("2021-08-13T22:38:18", "Моя мечта", "img/coast-medium.jpg", "Лариса", 1, 3, 14),
       ("2021-09-01T22:38:18", "Лучшие курсы", "www.htmlacademy.ru", "Владик", 3, 2, 49);

-- Комментарии от пользователей
INSERT INTO post_comments (created_at, content, user_id, post_id)
VALUES ("2021-09-13T22:38:18", "Я так долго ждала что-то подобное, обязательно воспользуюсь.", 1, 6),
       ("2021-09-15T22:38:18", "Я там бывал, отличное место, стремитесь к своей мечте!!!", 2, 5);

-- Выбор заголовка, автора и типа поста.
SELECT title, users.login, content_types.name
FROM posts
       INNER JOIN users
                  ON posts.user_id = users.id
       INNER JOIN content_types
                  ON posts.type_id = content_types.id
ORDER BY views_count;

-- Выбираем все посты одного пользователя
SELECT created_at,
       title,
       content,
       author,
       picture_url,
       video_url,
       website,
       views_count
FROM posts
WHERE user_id > (SELECT id FROM users LIMIT 1);

-- Выбираем один пост и получаем логин пользователя из таблицы users
SELECT created_at, content, users.login
FROM post_comments
       INNER JOIN users
                  ON post_comments.user_id = users.id
WHERE post_id = 6;

-- Добавление лайка пользователем Вадик к посту с id 3
INSERT INTO likes (user_id, post_id)
VALUES (3, 3);

-- Лариса подписалась на Виктора
INSERT INTO subscribes (author_id, subscribe_id)
VALUES (2, 1);
