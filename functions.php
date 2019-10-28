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

function counts_projects($projects_list, $name)
{
    $categories_array = array_map(function ($item) {
        return $item['category'];
    }, $projects_list);

    return array_reduce($categories_array, function ($carry, $item) use ($name) {
        $carry += $item === $name ? 1 : 0;
        return $carry;
    }, 0);
}
