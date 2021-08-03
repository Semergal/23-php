<?php
$example_persons_array = [
    [
        'fullname' => 'Иванов Иван Иванович',
        'job' => 'tester',
    ],
    [
        'fullname' => 'Степанова Наталья Степановна',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Пащенко Владимир Александрович',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Громов Александр Иванович',
        'job' => 'fullstack-developer',
    ],
    [
        'fullname' => 'Славин Семён Сергеевич',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Цой Владимир Антонович',
        'job' => 'frontend-developer',
    ],
    [
        'fullname' => 'Быстрая Юлия Сергеевна',
        'job' => 'PR-manager',
    ],
    [
        'fullname' => 'Шматко Антонина Сергеевна',
        'job' => 'HR-manager',
    ],
    [
        'fullname' => 'аль-Хорезми Мухаммад ибн-Муса',
        'job' => 'analyst',
    ],
    [
        'fullname' => 'Бардо Жаклин Фёдоровна',
        'job' => 'android-developer',
    ],
    [
        'fullname' => 'Шварцнегер Арнольд Густавович',
        'job' => 'babysitter',
    ],
];

function getFullnameFromParts($surname, $name, $patronymic)
{
    $full_name = $surname . ' ' . $name . ' ' . $patronymic;
    return $full_name;
}

function getPartsFromFullname($full_name)
{
    $arr_value = explode(' ', $full_name);
    $arr_key = [
        'surname', 
        'name', 
        'patronymic', 
    ];

    return array_combine($arr_key, $arr_value);
}

function getShortName($full_name)
{
    $arr = getPartsFromFullname($full_name);
    $short_name = $arr['name'] . ' ' . mb_substr($arr['surname'], 0, 1) . '.';
    return $short_name;
}

function  getGenderFromName($full_name)
{
    $arr = getPartsFromFullname($full_name);
    $gender = 0;
    $nameEnding = mb_substr($arr['name'],-1,1);
    $surnameEnding = mb_substr($arr['surname'],-2,2);
    $patronymicEnding = mb_substr($arr['patronymic'],-3,3);

    if ($nameEnding == 'а')
    {
        $gender--;
    }
    elseif ($nameEnding == 'й' || $nameEnding == 'н')
    {
        $gender++;
    }

    if ($surnameEnding == 'ва')
    {
        $gender--;
    }
    elseif (mb_substr($surnameEnding,-1,1) == 'в')
    {
        $gender++;
    }

    if ($patronymicEnding == 'вна')
    {
        $gender--;
    }
    elseif (mb_substr($patronymicEnding,-2,2) == 'ич')
    {
        $gender++;
    }

    return $gender <=> 0;
}

function getGenderDescription($arr)
{
    foreach ($arr as $item){
        $arrayNew[] = getGenderFromName($item['fullname']);
    }

    $arrWithMan = array_filter($arrayNew, function($gender){
        if ($gender == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    });

    $arrWithWoman = array_filter($arrayNew, function($gender){
        if ($gender == -1)
        {
            return true;
        }
        else
        {
            return false;
        }
    });

    $arrWithUndefined = array_filter($arrayNew, function($gender){
        if ($gender == 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    });

    $result = 'Гендерный состав аудитории:<br>' . '---------------------------<br>';
    $manCount = round((count($arrWithMan) / count($arrayNew)) * 100, 2);
    $result .= "Мужчины - $manCount%<br>";
    $womanCount = round((count($arrWithWoman) / count($arrayNew)) * 100, 2);
    $result .= "Женщины - $womanCount%<br>";
    $undefinedCount = round((count($arrWithUndefined) / count($arrayNew)) * 100, 2);
    $result .= "Не удалось определить - $undefinedCount%<br>";

    echo $result;    
}

function getPerfectPartner($surname, $name, $patronymic, $arr)
{
    $surname = mb_convert_case($surname, MB_CASE_LOWER_SIMPLE);
    $surname =  mb_convert_case($surname, MB_CASE_TITLE_SIMPLE);
    $name = mb_convert_case($name, MB_CASE_LOWER_SIMPLE);
    $name =  mb_convert_case($name, MB_CASE_TITLE_SIMPLE);
    $patronymic = mb_convert_case($patronymic, MB_CASE_LOWER_SIMPLE);
    $patronymic =  mb_convert_case($patronymic, MB_CASE_TITLE_SIMPLE);
    
    $full_name = getFullnameFromParts($surname, $name, $patronymic);
    $gender = getGenderFromName($full_name);
    $full_name_partner;

    $count = count($arr);
    
    do
    {
        $randomPartner = rand(0, $count-1);
        $partnerGender = getGenderFromName($arr[$randomPartner]['fullname']);
        $full_name_partner = $arr[$randomPartner]['fullname'];
    }
    while($partnerGender + $gender != 0);

    $percent = number_format(rand(0, 10000) / 100, 2, '.', '');
    $result = getShortName($full_name) . ' + ' . getShortName($full_name_partner) . " = <br> ";
    $result .= "&#9829" . ' ' . "Идеaльно на $percent%" . ' ' . "&#9829";

    echo $result;
}

getGenderDescription($example_persons_array);
echo "<br>";
getPerfectPartner('Иванов', 'Иван', 'Иванович', $example_persons_array);

?>