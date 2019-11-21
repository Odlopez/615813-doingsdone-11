INSERT INTO projects (name, user_id)
VALUES ('Входящие', 1), ('Учеба', 1), ('Работа', 1), ('Домашние дела', 1), ('Авто', 1);

INSERT INTO projects (name, user_id)
VALUES ('Спальные', 2), ('Едальные', 2), ('Отдыхальные', 2), ('Фантастические', 2);

INSERT INTO users (name, email, password)
VALUES ('odlopez', 'odlopez@inbox.ru', '$2y$10$3DGc4R1UuIwOjbRIyOd52.JV2ig8MvLsgMILeFlFLcqtYoohQLxBq'), ('kostyan', 'warrior2008@gmail.com', 'vfvfcrfpfkfzrhfcbdsq');

INSERT INTO tasks (name, project_id, deadline, is_done, file_name, file_path)
VALUES ('Собеседование в IT компании', 3, DATE('2019.12.01'), 0, NULL, NULL),
   ('Выполнить тестовое задание', 3, DATE('2019.11.09'), 0, NULL, NULL),
   ('Сделать задание первого раздела', 2, DATE('2019.11.05'), 1, NULL, NULL),
   ('Встреча с другом', 1, DATE('2019.12.22'), 0, NULL, NULL),
   ('Купить корм для кота', 4, NULL, 0, NULL, NULL),
   ('Заказать пиццу', 4, NULL, 0, NULL, NULL);

INSERT INTO tasks (name, project_id, deadline, is_done, file_name, file_path)
VALUES ('Выспаться', 6, DATE('2019.11.09'), 0, NULL, NULL),
       ('Сварить картоху', 7, DATE('2019.11.09'), 0, NULL, NULL),
       ('Победить грибы в кастрюле', 7, DATE('2019.11.05'), 0, NULL, NULL),
       ('Пройти StarCraft 2', 8, DATE('2019.12.22'), 1, NULL, NULL),
       ('Пожамкать Светку', 9, NULL, 0, NULL, NULL),
       ('Стать Росомахой', 9, NULL, 0, NULL, NULL);

SELECT * FROM projects WHERE user_id = 1;

SELECT * FROM tasks WHERE project_id = 3;

UPDATE  tasks SET is_done = 1 WHERE id = 6;

UPDATE  tasks SET name = 'Купить корм для лохматой нечисти' WHERE id = 5;
