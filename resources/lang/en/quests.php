<?php

return [
    'characters'    => [
        'create'    => [
            'description'   => 'Set a character to a Quest',
            'success'       => 'Character added to :name.',
            'title'         => 'New Character for :name',
        ],
        'destroy'   => [
            'success'   => 'Quest character for :name removed.',
        ],
        'edit'      => [
            'description'   => '',
            'success'       => 'Quest character for :name updated.',
            'title'         => 'Update character for :name',
        ],
        'fields'    => [
            'character'     => 'Character',
            'description'   => 'Description',
        ],
    ],
    'create'        => [
        'description'   => '',
        'success'       => 'Quest \':name\' created.',
        'title'         => 'Create a new quest',
    ],
    'destroy'       => [
        'success'   => 'Quest \':name\' removed.',
    ],
    'edit'          => [
        'description'   => '',
        'success'       => 'Quest \':name\' updated.',
        'title'         => 'Edit Quest :name',
    ],
    'fields'        => [
        'characters'    => 'Characters',
        'description'   => 'Description',
        'image'         => 'Image',
        'locations'     => 'Locations',
        'name'          => 'Name',
        'type'          => 'Type',
    ],
    'index'         => [
        'add'           => 'New Quest',
        'description'   => 'Manage the quests of :name.',
        'header'        => 'Quests of :name',
        'title'         => 'Quests',
    ],
    'locations'     => [
        'create'    => [
            'description'   => 'Set an location to a Quest',
            'success'       => 'Location added to :name.',
            'title'         => 'New Location for :name',
        ],
        'destroy'   => [
            'success'   => 'Quest location for :name removed.',
        ],
        'edit'      => [
            'description'   => '',
            'success'       => 'Quest location for :name updated.',
            'title'         => 'Update location for :name',
        ],
        'fields'    => [
            'description'   => 'Description',
            'location'      => 'Location',
        ],
    ],
    'placeholders'  => [
        'name'  => 'Name of the quest',
        'type'  => 'Character Arc, Sidequest, Main',
    ],
    'show'          => [
        'actions'       => [
            'add_character' => 'Add a character',
            'add_location'  => 'Add a location',
        ],
        'description'   => 'A detailed view of an quest',
        'tabs'          => [
            'characters'    => 'Characters',
            'information'   => 'Information',
            'locations'     => 'Locations',
        ],
        'title'         => 'Quest :name',
    ],
];