<?php

return [
    [
        'group' => 'Провода и кабели',
        'items' => [
            ['title' => 'Провода', 'modelName' => 'OverheadWireInfo'],
            ['title' => 'Кабели', 'modelName' => 'CableInfo'],
        ]
    ],
    [
        'group' => 'Коммутационные аппараты',
        'items' => [
            ['title' => 'Выключатели', 'modelName' => 'BreakerInfo'],
            ['title' => 'Выключатели нагрузки', 'modelName' => 'LoadBreakSwitchInfo'],
            ['title' => 'Разъеденители', 'modelName' => 'DisconnectorInfo'],
            ['title' => 'Предохранители', 'modelName' => 'FuseInfo'],
            ['title' => 'Реклоузеры', 'modelName' => 'RecloserInfo'],
        ]
    ],
    [
        'group' => 'Силовые трансформаторы',
        'items' => [
            ['title' => 'Силовые трансформаторы', 'modelName' => 'OldTransformerTankInfo'],
        ]
    ],
    [
        'group' => 'Трансформатор тока',
        'items' => [
            ['title' => 'Трансформатор тока', 'modelName' => 'CurrentTransformerInfo'],
        ]
    ],
    [
        'group' => 'Трансформатор напряжения',
        'items' => [
            ['title' => 'Трансформатор напряжения', 'modelName' => 'PotentialTransformerInfo'],
        ]
    ],
];
