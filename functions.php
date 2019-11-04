<?php
function getAllProjects()
{
    return ['Входящие', 'Учеба', 'Работа', 'Домашние дела', 'Авто'];
}

function getAllTasks()
{
    return [
        [
            'task' => 'Собеседование в IT компании',
            'date' => '01.12.2019',
            'category' => 'Работа',
            'isDone' =>  false,
            'id' => 0
        ],
        [
            'task' => 'Выполнить тестовое задание',
            'date' => '25.12.2019',
            'category' => 'Работа',
            'isDone' =>  false,
            'id' => 1
        ],
        [
            'task' => 'Сделать задание первого раздела',
            'date' => '21.12.2019',
            'category' => 'Учеба',
            'isDone' =>  true,
            'id' => 2
        ],
        [
            'task' => 'Встреча с другом',
            'date' => '22.12.2019',
            'category' => 'Входящие',
            'isDone' =>  false,
            'id' => 3
        ],
        [
            'task' => 'Купить корм для кота',
            'date' => null,
            'category' => 'Домашние дела',
            'isDone' =>  false,
            'id' => 4
        ],
        [
            'task' => 'Заказать пиццу',
            'date' => null,
            'category' => 'Домашние дела',
            'isDone' =>  false,
            'id' => 5
        ]
    ];
}

/**
 * Возвращает количество совпадающих по назанию категорий в переданном массиве проектов
 * @param array $tasks_list массив с задачами
 * @param string $parameter_value имя категории
 * @return int количество сопадающих категорий
 */
function counts_category_in_tasks(array $tasks_list, string $parameter_value): int
{
    return array_reduce($tasks_list, function ($carry, $item_task) use ($parameter_value) {
        $carry += $item_task['category'] === $parameter_value ? 1 : 0;

        return $carry;
    }, 0);
}
