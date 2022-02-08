<?php
return [
    [
        'group' => 'Провода и кабели',
        'items' => [
            ['title' => 'Вид материала проводника', 'modelName' => 'WireMaterialKind', 'enumKind' => false],
            ['title' => 'Вид материала изоляции', 'modelName' => 'WireInsulationKind', 'enumKind' => false],
            ['title' => 'Вид конструктивного исполнения токопроводящих жил', 'modelName' => 'CableConstructionKind', 'enumKind' => false],
            ['title' => 'Вид материала экрана', 'modelName' => 'CableShieldMaterialKind', 'enumKind' => false],
            ['title' => 'Вид материала наружной оболочки', 'modelName' => 'CableOuterJacketKind', 'enumKind' => false],
            ['title' => 'Вид исполнения по пожароопасности (по ГОСТ Р 53315-2009)', 'modelName' => 'EnproFireSafetyKind', 'enumKind' => false],
        ]
    ],
    [
        'group' => 'Силовые трансформаторы',
        'items' => [
            ['title' => 'Вид типа конструкции', 'modelName' => 'TransformerConstructionKind', 'enumKind' => false],
            ['title' => 'Вид типа сердечника', 'modelName' => 'TransformerCoreKind', 'enumKind' => false],
            ['title' => 'Вид функционального назначения трансформатора', 'modelName' => 'TransformerFunctionKind', 'enumKind' => false],
            ['title' => 'Вид охлаждения трансформатора', 'modelName' => 'TransformerCoolingKind', 'enumKind' => false],
            ['title' => 'Вид схемы соединения обмотки трансформатора', 'modelName' => 'WindingConnection', 'enumKind' => true],
            ['title' => 'Вид изоляции обмотки трансформатора', 'modelName' => 'WindingInsulationKind', 'enumKind' => false],
        ]
    ],
    [
        'group' => 'Коммутационные аппараты',
        'items' => [
            ['title' => 'Вид среды дугогасящей камеры выключателя', 'modelName' => 'BreakerConstructionKind', 'enumKind' => false],
            ['title' => 'Вид расположения ДГУ', 'modelName' => 'InterrupterPositionKind', 'enumKind' => false],
            ['title' => 'Вид напряжения питания вкл. и отк. устройств, вспом. цепей и цепей упр.', 'modelName' => 'SecondaryCircuitsVoltageKind', 'enumKind' => false],
            ['title' => 'Климатическое исполнение и категория размещения', 'modelName' => 'GostClimaticModPlacementKind', 'enumKind' => false],
        ]
    ],
    [
        'group' => 'КТП',
        'items' => [
            ['title' => 'Виды типа силового трансформатора', 'modelName' => 'KTPTransformerTypeKind', 'enumKind' => false],
            ['title' => 'Виды cпособа выполнения нейтрали', 'modelName' => 'KTPNeutralTypeKind', 'enumKind' => false],
            ['title' => 'Виды взаимного расположения изделий', 'modelName' => 'KTPInnerCompositionKind', 'enumKind' => false],
            ['title' => 'Виды числа силовых трансформаторов', 'modelName' => 'KTPTransformerNumberKind', 'enumKind' => false],
            ['title' => 'Виды изоляции шин в РУНН', 'modelName' => 'KTPBusBarInsulationKind', 'enumKind' => false],
            ['title' => 'Виды ввода ВН', 'modelName' => 'KTPUpperVoltageInputKind', 'enumKind' => false],
            ['title' => 'Виды выводов в РУНН', 'modelName' => 'KTPLowVoltageOutputKind', 'enumKind' => false],
            ['title' => 'Виды способа установки автоматических выключателей', 'modelName' => 'KTPCircuitBreakerInstallationTypeKind', 'enumKind' => false],
            ['title' => 'Виды уровня изоляции (по ГОСТ 1516.1-76)', 'modelName' => 'GOSTInsulationLevelKind', 'enumKind' => false],
        ]
    ],
    [
        'group' => 'Прочее',
        'items' => [
            ['title' => 'Виды конструкции трансформатор напряжения', 'modelName' => 'PotentialTransformerKind', 'enumKind' => false],
            ['title' => 'Виды области применения транформатора', 'modelName' => 'TransformerApplicationКind', 'enumKind' => false],
            ['title' => 'Виды заземления экрана', 'modelName' => 'ShieldGroundingKind', 'enumKind' => false],
            ['title' => 'Виды конструкции КЛ', 'modelName' => 'UndergroundStructureKind', 'enumKind' => false],
            ['title' => 'Виды состояния внедрения', 'modelName' => 'DeploymentStateKind', 'enumKind' => false],
            ['title' => 'Виды типа сооружения', 'modelName' => 'FacilityKind', 'enumKind' => false],
            ['title' => 'Виды состояния готовности к работе', 'modelName' => 'InUseStateKind', 'enumKind' => false],
            ['title' => 'Виды конструктивного типа подстанции', 'modelName' => 'FacilityConstructionKind', 'enumKind' => false],
        ]
    ],
];








