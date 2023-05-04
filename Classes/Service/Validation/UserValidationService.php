<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension t3templates_base.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Kevin Chileong Lee, info@wacon.de, WACON Internet GmbH
 */


 namespace Wacon\Feuserregistration\Service\Validation;

use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Error\Result;
use TYPO3\CMS\Extbase\Error\Error;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class UserValidationService extends AbstractValidationService {
    /**
     * List of required fields of the BookingRequest Form
     * @var array
     */
    protected $requiredFields = ['email'];

    /**
     * @var \TYPO3\CMS\Extbase\Validation\Validator\EmailAddressValidator $emailAddressValidator
     */
    protected $emailAddressValidator;

    /**
     * @var \Wacon\Feuserregistration\Domain\Repository\UserRepository $userRepository
     */
    protected $userRepository;

    /**
     * Create a BookingRequestValidationService
     * @param \TYPO3\CMS\Extbase\Validation\Validator\EmailAddressValidator $emailAddressValidator
     * @return void
     */
    public function __construct(
        \TYPO3\CMS\Extbase\Validation\Validator\EmailAddressValidator $emailAddressValidator,
        \Wacon\Feuserregistration\Domain\Repository\UserRepository $userRepository
    ) {
        $this->emailAddressValidator = $emailAddressValidator;
        $this->userRepository = $userRepository;
    }

    /**
     * Check if object is valid
     * @param mixed $value
     * @return bool
     */
    public function isValid($value) {        
        /**
         * @var User $value
         */
        $this->reset();        
        $requiredFieldsThatAreEmpty = [];
        $errors = [];

        foreach($this->requiredFields as $fieldName) {
            $func = 'get' . ucfirst($fieldName);
            $val = $value->$func();

            if (empty($val)) {
                $requiredFieldsThatAreEmpty[] = $fieldName;
            }else {
                switch($fieldName) {
                    case 'email':
                        $result = $this->emailAddressValidator->validate($val);
                        
                        if ($result->hasErrors()) {
                            $errors[$fieldName] = $result->getErrors();
                        }
                        break; 
                }
            }
        }

        if(count($requiredFieldsThatAreEmpty) > 0) {
            foreach($requiredFieldsThatAreEmpty as $field) {
                $this->propertiesWithError[] = [
                    'name' => $field,
                    'errorString' => LocalizationUtility::translate('validation.error.notempty', $this->extensionName),
                    'errorCode' => time()
                ];
            }
        }

        // check if user already exists with that email
        $exists = $this->userRepository->findByEmail($value->getEmail())->current();

        if ($exists) {
            $this->propertiesWithError[] = [
                'name' => 'email',
                'errorString' => LocalizationUtility::translate('validation.error.email.exists', $this->extensionName),
                'errorCode' => time()
            ];
        }

        if (count($errors) > 0) {
            foreach($errors as $fieldName => $errors) {
                foreach($errors as $error) {
                    $errorString = LocalizationUtility::translate($error->getMessage(), $this->extensionName);
                    
                    if (empty($errorString)) {
                        $errorString = $error->getMessage();
                    }

                    $this->propertiesWithError[] = [
                        'name' => $fieldName,
                        'errorString' => $errorString,
                        'errorCode' => time()
                    ];
                }
            }
        }
    }
}