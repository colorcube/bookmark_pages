<?php

/*
 * This file is part of the package buepro/bookmark_pages.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$EM_CONF[$_EXTKEY] = [
    'title' => 'Bookmark Pages',
    'description' => 'Provides bookmarks functionality of local pages for logged in frontend users.',
    'category' => 'plugin',
    'author' => 'René Fritz, Roman Büchler',
    'author_email' => 'r.fritz@colorcube.de, rb@buechler.pro',
    'author_company' => 'Colorcube, buechler.pro gmbh',
    'version' => '2.0.0-dev',
    'state' => 'stable',
    'uploadfolder' => 0,
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 1,
    'constraints' => [
        'depends' => [
            'typo3' => '10.4.14-10.4.99',
            'typoscript_rendering' => '2.3.1-2.99.99',
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
