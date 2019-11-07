INSERT INTO projects (name, user_id)
VALUES ('Входящие', 1), ('Учеба', 1), ('Работа', 1), ('Домашние дела', 1), ('Авто', 1);

INSERT INTO users (name, email, password)
VALUES ('odlopez', 'odlopez@inbox.ru', ',ekjxrfc,htdyjv'), ('kostyan', 'warrior2008@gmail.com', 'vfvfcrfpfkfzrhfcbdsq');

INSERT INTO tasks (name, project_id, deadline, is_done, file)
VALUES ('Собеседование в IT компании', 3, DATE('2019.12.01'), 0, NULL),
   ('Выполнить тестовое задание', 3, DATE('2019.11.08'), 0, NULL),
   ('Сделать задание первого раздела', 2, DATE('2019.11.05'), 1, NULL),
   ('Встреча с другом', 1, DATE('2019.12.22'), 0, NULL),
   ('Купить корм для кота', 4, NULL, 0, NULL),
   ('Заказать пиццу', 4, NULL, 0, NULL);

SELECT * FROM projects WHERE user_id = 1;

SELECT * FROM tasks WHERE project_id = 3;

UPDATE  tasks SET is_done = 1 WHERE id = 6;

UPDATE  tasks SET name = 'Купить корм для лохматой нечисти' WHERE id = 5;