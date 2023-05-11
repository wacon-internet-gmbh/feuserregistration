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
            ],
            'first_name' => [
                'fieldName' => 'firstName'
            ],
            'last_name' => [
                'fieldName' => 'lastName'
            ],
            'address' => [
                'fieldName' => 'address'
            ],
            'zip' => [
                'fieldName' => 'zip'
            ],
            'city' => [
                'fieldName' => 'city'
            ],
            'country' => [
                'fieldName' => 'country'
            ],
            'phone' => [
                'fieldName' => 'phone'
            ],
            'fax' => [
                'fieldName' => 'fax'
            ],
            'www' => [
                'fieldName' => 'www'
            ],
            'luxletter_language' => [
                'fieldName' => 'luxletterLanguage'
            ],
        ]
    ]
];