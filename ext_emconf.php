<?php
$EM_CONF[$_EXTKEY] = [
    'title' => 'Frontend User Registration',
    'description' => 'This extension enables the easy registration of a frontend user using the double opt-in process (e.g., for newsletter sign-ups). Optionally, a notification can be sent to a centrally configured email address.',
    'category' => 'plugin',
    'author' => 'Kevin Chileong Lee',
    'author_email' => 'info@wacon.de',
    'author_company' => 'WACON Internet GmbH',
    'state' => 'stable',
    'version' => '2.5.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-13.4.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
