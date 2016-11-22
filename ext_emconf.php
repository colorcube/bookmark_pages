<?php


$EM_CONF[$_EXTKEY] = array(
    'title' => 'Bookmark Pages',
    'description' => 'Provide bookmarks functionality of local pages for logged in frontend users.',
    'category' => 'Plugins',
    'author' => 'René Fritz',
    'author_email' => '',
    'author_company' => '',
    'shy' => '',
    'priority' => '',
    'module' => '',
    'state' => 'alpha',
    'internal' => '',
    'uploadfolder' => '0',
    'createDirs' => '',
    'modify_tables' => '',
    'clearCacheOnLoad' => 1,
    'lockType' => '',
    'version' => '0.0.1',
    'constraints' => array(
        'depends' => array(
            'php' => '5.3.7-7.0.999',
            'typo3' => '6.2.0-7.6.999',
            'typoscript_rendering' => '*',
        ),
        'conflicts' => array(
        ),
        'suggests' => array(
            'news' => '*',
        ),
    ),
);
