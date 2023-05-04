<?php
return [
    \Wacon\Feuserregistration\Domain\Model\User::class => [
        'tableName' => 'fe_users',
        'recordType' => 0,
        'properties' => [
            'email' => [
                'fieldName' => 'email'
            ],
            'doi_hash' => [
                'fieldName' => 'doiHash'
            ],
            'disable' => [
                'fieldName' => 'disable'
            ],
            'usergroup' => [
                'fieldName' => 'usergroup'
            ]
        ]
    ]
];