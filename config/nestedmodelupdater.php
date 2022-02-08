<?php

return [

    // Enable database transactions for top-level create/update operations.
    'database-transactions' => true,

    // Allows using temporary ids to refer to records that are not created yet.
    'allow-temporary-ids' => false,

    // Allows updating of trashed (nested) models.
    'allow-trashed' => false,

    // If allowed, the key to look for temporary create ids to look for
    'temporary-id-key' => '_tmp_id',

    // List of FQNs of relation classes that are of the to One type. Every
    // other relation is considered plural.
    'singular-relations' => [
        \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
        \Illuminate\Database\Eloquent\Relations\HasOne::class,
        \Illuminate\Database\Eloquent\Relations\MorphOne::class,
        \Illuminate\Database\Eloquent\Relations\MorphTo::class,

        '\Znck\Eloquent\Relations\BelongsToThrough',
    ],

    // List of FQNs of relation classes that have their ids stored as
    // foreign keys on the parent class of the relation. Any nested update
    // operation on one of these will be performed before updating or
    // creating the parent model.
    'belongs-to-relations' => [
        \Illuminate\Database\Eloquent\Relations\BelongsTo::class,
        \Illuminate\Database\Eloquent\Relations\MorphTo::class,
    ],

    // Definitions for nested updatable relations, per parent model FQN as key.
    // Each definition should be keyed by its attribute key (as it would be set in
    // an update data array (typically snake cased).
    //
    // This may store:
    //
    //      link-only       boolean     true if we're not allowed to update through nesting (default: false).
    //      update-only     boolean     true if we're not allowed to create through nesting (default: false).
    //      updater         string      FQN of ModelUpdaterInterface class that should handle things.
    //      method          string      method name for the relation, if not camelcased attribute key.
    //      detach          boolean     if true, performs detaching sync for BelongsToMany, dissociates
    //                                  children in HasMany, relations not present in the update data.
    //                                  (default: true for BelongsToMany, false for HasMany)
    //      delete-detached boolean     if true, deletes instead of detaching. for HasMany relations this
    //                                  means that instead of setting the foreign key NULL, for BelongsToMany
    //                                  related models are deleted if they are not related to anything else
    //                                  (default: false).
    //      validator       string      FQN of NestedValidatorInterface that should handle validation.
    //      rules           string      FQN of the class that provides the rules for the model
    //      rules-method    string      method name on the rules class to use (default: 'rules')
    //
    //
    //          'Some\Model\Class' => [
    //              'relation_key' => [ 'link-only' => true, 'updater' => 'Some\Updater\Class' ]
    //          ]
    //
    // Alternatively, set the key's value to boolean true to use defaults and allow full updates.
    //
    //          'Some\Model\Class\' => [ 'relation_key' => true ]
    //
    // If a relation is not present in this config, no nested updating or linking will
    // allowed at all.
    //
    'relations' => [
        App\Models\WireInfo::class => [
            // the data nested relation attribute
            // with a value of true to allow updates with default settings
            'coreRadius' => true,
            'gmr' => true,
            'insulationThickness' => true,
            'rAC25' => true,
            'rAC50' => true,
            'rAC75' => true,
            'radius' => true,
            'ratedCurrent' => true,
            'rDC20' => true,
            'enproBreakForce' => true,
            'enproWeightPerLength' => true,
            'OverheadWireInfo' => true,
            'CableInfo' => true,
            'nominalVoltage' => true,
            'standardServiceLife' => true,
        ],
        App\Models\CableInfo::class => [
            'diameterOverCore' => true,
            'diameterOverInsulation' => true,
            'diameterOverJacket' => true,
            'diameterOverScreen' => true,
            'nominalTemperature' => true,
            'WireInfo' => true,
        ],
        App\Models\WireAssemblyInfo::class => [
            'WirePhaseInfo' => [
                'detach' => true,
                'delete-detached' => true,
            ],
            'AssetInfo' => true,
        ],
        App\Models\WirePhaseInfo::class => [
            'phaseInfo' => true,
            'WireInfo' => true,
        ],
        App\Models\AssetInfo::class => [
            'CatalogAssetType' => true,
            'IdentifiedObject' => true,
        ],
        App\Models\CatalogAssetType::class => [
            'IdentifiedObject' => true,
        ],
        App\Models\Identifiedobject::class => [
            'names' => true,
        ],
        //OldTransformerTankInfo
        App\Models\OldTransformerTankInfo::class => [
            'TransformerTankInfo' => true,
            'coreCoilsWeight' => true,
            'enproFullWeight' => true,
            'enproOilWeight' => true,
            'enproTemperatureRange' => true,
        ],
        App\Models\TemperatureRange::class => [
            'minTemperature' => true,
            'maxTemperature' => true,
        ],
        App\Models\TransformerTankInfo::class => [
            'AssetInfo' => true,
            'TransformerEndInfo' => true,
        ],
        App\Models\TransformerEndInfo::class => [
            'OldTransformerEndInfo' => true,
            'ShortCircuitTests' => [
                'detach' => true,
                'delete-detached' => true,
            ],
            'NoLoadTests' => [
                'detach' => true,
                'delete-detached' => true,
            ],
            'ratedS' => true,
            'ratedU' => true,
            'r' => true,
            'AssetInfo' => true,
        ],
        App\Models\ShortCircuitTest::class => [
            'loss' => true,
            'voltage' => true,
            'GroundedEnds' => true,
            'TransformerTest' => true,
        ],
        App\Models\NoLoadTest::class => [
            'loss' => true,
            'excitingCurrent' => true,
            'TransformerTest' => true,
        ],
        App\Models\TransformerTest::class => [
            'temperature' => true,
        ],
        //SwitchInfo
        App\Models\SwitchInfo::class => [
            'OldSwitchInfo' => true,
            'AssetInfo' => true,
            'ratedVoltage' => true,
            'enproMaxVoltage' => true,
            'ratedFrequency' => true,
            'ratedCurrent' => true,
            'breakingCapacity' => true,
            'ratedInterruptingTime' => true,
            'ratedImpulseWithstandVoltage' => true,
            'enproRatedPressure' => true,
            'lowPressureAlarm' => true,
            'lowPressureLockOut' => true,
            'enproBreakForce' => true,
            'enproInsulationLength' => true,
            'enproTemperatureRange' => true,
            'gasWeightPerTank' => true,
            'oilVolumePerTank' => true,
        ],
        App\Models\OldSwitchInfo::class => [
            'withstandCurrent' => true,
            'makingCapacity' => true,
            'enproWithstandCurrentDuration' => true,
            'enproEarthSwitchCurrentDuration' => true,
            'enproSecondaryVoltage' => true,
            'BreakerInfo' => true,
            'RecloserInfo' => true,
            'FuseInfo' => true,
            'DisconnectorInfo' => true,
            'LoadBreakSwitchInfo' => true,
        ],
        App\Models\Acline::class => [
            'asset' => true,
        ],
        App\Models\Asset::class => [
            'IdentifiedObject' => true,
            'AssetGroups' => true
        ],
        App\Models\Currenttransformerinfo::class => [
            'AssetInfo' => true,
            'ratedVoltage' => true,
            'enproMaxVoltage' => true,
            'ratedFrequency' => true,
            'ratedCurrent' => true,
            'nominalRatio' => true,
            'enproClimaticModPlacement' => true,
        ],
        App\Models\PotentialTransformerInfo::class => [
            'AssetInfo' => true,
            'ratedFrequency' => true,
            'enproClimaticModPlacement' => true,
            'enproConstructionKind' => true,
            'enproSecondary1Voltage' => true,
            'enproSecondary2Voltage' => true,
            'ratedVoltage' => true,
        ]
    ],

    // Settings for using the nested data validator
    'validation' => [

        // Default namespace to look for <ModelName> classes with rules in
        // If no rules class has been defined for a specific model, the
        // model name is expected in this namespace.
        //
        // The rules relation configuration option overrides this and the next
        // configuration option.
        'model-rules-namespace' => 'App\\Http\\Requests\\Rules',

        // Postfix to use when composing model rules class FQNs.
        // If this is set to 'Rules', the class name loaded would
        // be <ModelName>Rules, f.i.: App\Http\Request\Rules\PostRules
        'model-rules-postfix' => null,

        // Default rules method to call on the rules classes
        // should take one optional parameter with the type:
        //  'create', 'update', (or 'link')
        //
        // The rules-method relation configuration option overrides this.
        'model-rules-method' => 'rules',

        // If true, does not throw exceptions if no rules model class can be
        // instantiated for a nested validation call.
        'allow-missing-rules' => true,


        // Classes and/or methods to read validation rules from by default for a given model
        // these settings will override the validation rules defaults, but will in turn
        // be overriden by specific rules classes and/or methods defined in the relations
        // configuration above.
        //
        // Definitions should be set per model FQN. They may either be a string indicating
        // the rules class (the default model-rules-method will be accessed on this class:
        //
        //          'Some\Model\Class' => 'Some\Rules\Class'
        //
        // or the configuration may be an array, with two optional settings: 'class' and 'rules':
        //
        //          'Some\Model\Class' => [
        //              'class'  => 'Some\Rules\Class',
        //              'method' => 'rulesMethod',
        //          ]
        //
        // Note that all classes defined here must be FQN's, they will *not* be namespaced by
        // the set model-rules-namespace.

        'model-rules' => [
        ],

    ],

];
