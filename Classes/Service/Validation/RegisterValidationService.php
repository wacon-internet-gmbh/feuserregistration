<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension feuserregistration.
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
use Wacon\Feuserregistration\Utility\Typo3\TypoScriptUtility;

class RegisterValidationService extends RegisterEmailValidationService {
    /**
     * List of required fields of the BookingRequest Form
     * @var array
     */
    protected $requiredFields = ['email'];

    /**
     * TypoScript Settings
     * @var array
     */
    protected $settings = [];

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

        $this->settings = TypoScriptUtility::getTypoScript('plugin.tx_feuserregistration.settings');
        
        if (!empty($this->settings['requiredFields'])) {
            $this->requiredFields = GeneralUtility::trimExplode(',', $this->settings['requiredFields'], true);
        }
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

            // Make sure, the target func exists
            if (!method_exists($value, $func)) {
                continue;
            }

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