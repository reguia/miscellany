<?php

return [
    'characters'    => [
        'description'   => 'Karakterek ezen a helyszínen',
        'title'         => ':name helyszín karakterei',
    ],
    'create'        => [
        'description'   => 'Új helyszín létrehozása',
        'success'       => ':name helyszínt létrehoztuk.',
        'title'         => 'Új helyszín',
    ],
    'destroy'       => [
        'success'   => ':name helyszínt töröltük.',
    ],
    'edit'          => [
        'success'   => ':name helyszínt frissítettük.',
        'title'     => ':name helyszín szerkesztése',
    ],
    'events'        => [
        'description'   => 'Események ezen a helyszínen',
        'title'         => ':name helyszín eseményei',
    ],
    'fields'        => [
        'characters'    => 'Karakterek',
        'image'         => 'Kép',
        'location'      => 'Helyszín',
        'locations'     => 'Helyszínek',
        'map'           => 'Térkép',
        'name'          => 'Név',
        'relation'      => 'Kapcsolat',
        'type'          => 'Típus',
    ],
    'helpers'       => [
        'descendants'   => 'Ez a lista a helyszín összes leszármazott helyszínét tartalmazza, nemcsak a közvetlen alhelyszíneit.',
        'nested'        => 'Hierarchikus nézetben a helyszíneidet alá-fölérendeltségi viszonyukban tekintheted meg. Alapesetben a szülő helyszín nélküli helyszínek látszanak, rájuk kattintva megtekintheted azok alhelyszíneit.',
    ],
    'index'         => [
        'actions'       => [
            'explore_view'  => 'Hierarchikus nézet',
        ],
        'add'           => 'Új helyszín',
        'description'   => ':name helyszín kezelése',
        'header'        => ':name helyszínei',
        'title'         => 'Helyszínek',
    ],
    'items'         => [
        'description'   => 'Tárgyak ezen a helyszínen vagy erről a helyszínről',
        'title'         => ':name helyszín tárgyai',
    ],
    'locations'     => [
        'description'   => 'A helyszín alhelyszínei',
        'title'         => ':name helyszín alhelyszínei',
    ],
    'map'           => [
        'actions'   => [
            'admin_mode'    => 'Szerkesztési mód bekapcsolása',
            'big'           => 'Teljes nézet',
            'download'      => 'Letöltés',
            'points'        => 'Pontok szerkesztése',
            'toggle_hide'   => 'Pontok elrejtése',
            'toggle_show'   => 'Pontok megjelenítése',
            'view_mode'     => 'Vissza a megtekintési nézetbe',
            'zoom_in'       => 'Közelít',
            'zoom_out'      => 'Távolít',
            'zoom_reset'    => 'Alapértelmezett nagyítás',
        ],
        'helper'    => 'Kattints a térképre hogy egy új pontot adj a helyszínhez, vagy kattints egy létező pontra annak megváltoztatásához vagy törléséhez.',
        'helpers'   => [
            'admin' => 'Aktiváld, hogy bárhova a térképre kattintva létrehozhass új pontokat, kattintva a pontokra szerkeszd őket vagy mozgasd.',
            'label' => 'Ez a pont egy címke, sem több, sem kevesebb.',
            'view'  => 'Kattints bármelyik pontra annak részleteiért. Ctrl+egérgörgővel közelítheted vagy távolíthatod a térképet.',
        ],
        'modal'     => [
            'submit'    => 'Hozzáad',
            'title'     => 'Új pont célpontja',
        ],
        'no_map'    => 'Szerkesztéskor feltölthetsz egy térképet ehhez a helyszínhez, mely itt fog megjelenni.',
        'points'    => [
            'fields'        => [
                'axis_x'    => 'X tengely',
                'axis_y'    => 'Y tengely',
                'colour'    => 'Háttér színe',
                'icon'      => 'Ikon',
                'name'      => 'Felirat',
                'shape'     => 'Alakzat',
                'size'      => 'Méret',
            ],
            'helpers'       => [
                'location_or_name'  => 'Egy térképi pont mutathat egy létező helyszínre, vagy lehet egyszerűen csak egy felirat is.',
            ],
            'icons'         => [
                'anchor'        => 'Horgony',
                'anvil'         => 'Üllő',
                'apple'         => 'Alma',
                'aura'          => 'Aura',
                'axe'           => 'Balta',
                'beer'          => 'Sör',
                'biohazard'     => 'Biohazard',
                'book'          => 'Könyv',
                'bridge'        => 'Híd',
                'campfire'      => 'Tábortűz',
                'candle'        => 'Gyertya',
                'cat'           => 'Macska',
                'cheese'        => 'Sajt',
                'cog'           => 'Fogaskerék',
                'crown'         => 'Korona',
                'dead-tree'     => 'Halott fa',
                'diamond'       => 'Gyémánt',
                'dragon'        => 'Sárkány',
                'emerald'       => 'Smaragd',
                'entity'        => 'Célentitás képe',
                'fire'          => 'Tűz',
                'flask'         => 'Palack',
                'flower'        => 'Virág',
                'horseshoe'     => 'Patkó',
                'hourglass'     => 'Homokóra',
                'hydra'         => 'Hidra',
                'kaleidoscope'  => 'Kaleidoszkóp',
                'key'           => 'Kulcs',
                'lever'         => 'Kapcsoló',
                'meat'          => 'Hús',
                'octopus'       => 'Polip',
                'palm-tree'     => 'Pálma',
                'pin'           => 'Gombostű',
                'pine-tree'     => 'Fenyőfa',
                'player'        => 'Karakter',
                'potion'        => 'Üvegcse',
                'reactor'       => 'Reaktor',
                'repair'        => 'Szerelő',
                'sheep'         => 'Birka',
                'shield'        => 'Pajzs',
                'skull'         => 'Koponya',
                'snake'         => 'Kígyó',
                'spades-card'   => 'Pikk kártya',
                'sprout'        => 'Növényi hajtás',
                'sun'           => 'Nap',
                'tentacle'      => 'Csáp',
                'toast'         => 'Pirítós',
                'tombstone'     => 'Sírkő',
                'torch'         => 'Fáklya',
                'tower'         => 'Torony',
                'water-drop'    => 'Víz',
                'wooden-sign'   => 'Küldetés',
                'wrench'        => 'Villáskulcs',
            ],
            'modal'         => 'Térképi pont létrehozása vagy szerkesztése',
            'placeholders'  => [
                'axis_x'    => 'Bal pozíció',
                'axis_y'    => 'Felső pozíció',
                'name'      => 'A pont felirata, amennyiben nincs helyszín megadva.',
            ],
            'return'        => 'Vissza ide: :name',
            'shapes'        => [
                'circle'    => 'Kör',
                'square'    => 'Négyzet',
            ],
            'sizes'         => [
                'large'     => 'Nagy',
                'small'     => 'Kicsi',
                'standard'  => 'Közepes',
            ],
            'success'       => [
                'create'    => 'A térképi pontot létrehoztuk',
                'delete'    => 'A térképi pontot eltávolítottuk',
                'update'    => 'A térképi pontot frissítettük',
            ],
            'title'         => ':name helyszín térképi pontjai',
        ],
        'success'   => 'A térképi pontokat elmentettük.',
    ],
    'organisations' => [
        'description'   => 'Szervezetek ezen a helyszínen',
        'title'         => ':name helyszín szervezetei',
    ],
    'placeholders'  => [
        'location'  => 'Válassz ki egy szülő helyszínt!',
        'name'      => 'A helyszín neve',
        'type'      => 'Város, királyság, rom, táncklub',
    ],
    'quests'        => [
        'description'   => 'A helyszínhez tartozó küldetések',
        'title'         => ':name helyszín küldetései',
    ],
    'show'          => [
        'description'   => 'A helyszín részletes nézete',
        'tabs'          => [
            'characters'    => 'Karakterek',
            'events'        => 'Események',
            'information'   => 'Információ',
            'items'         => 'Tárgyak',
            'locations'     => 'Helyszínek',
            'map'           => 'Térkép',
            'menu'          => 'Menü',
            'organisations' => 'Szervezetek',
            'quests'        => 'Küldetések',
        ],
        'title'         => ':name helyszín',
    ],
];