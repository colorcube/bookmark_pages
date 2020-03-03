<?php


$EM_CONF[$_EXTKEY] = [
    'title' => 'Bookmark Pages',
    'description' => 'Provides bookmarks functionality of local pages for logged in frontend users.',
    'category' => 'plugin',
    'author' => 'René Fritz',
    'author_email' => 'r.fritz@colorcube.de',
    'author_company' => 'Colorcube',
    'version' => '1.2.2',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 1,
    'constraints' => [
        'depends' => [
            'typo3' => '6.2.0-8.7.999',
            'typoscript_rendering' => '*',
        ],
        'conflicts' => [],
        'suggests' => [
            'news' => '*'
        ],
    ],
    'autoload' => [
        'psr-4' => [
            'Colorcube\\BookmarkPages\\' => 'Classes'
        ]
    ]
];
