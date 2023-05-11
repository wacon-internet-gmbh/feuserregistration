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

namespace Wacon\Feuserregistration\Domain\Validator;

use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;
use TYPO3\CMS\Core\Utility\GeneralUtility;

class RegisterEmailValidator extends AbstractValidator
{
    /**
     * @var \Wacon\Feuserregistration\Service\Validation\RegisterEmailValidationService
     */
    protected $registerEmailValidationService;
    
    protected function isValid($value): void
    {
        $this->registerEmailValidationService = GeneralUtility::makeInstance(\Wacon\Feuserregistration\Service\Validation\RegisterEmailValidationService::class);        
        $className = get_class($value);

        if ($className != \Wacon\Feuserregistration\Domain\Model\User::class) {
            $errorString = 'The user validator can only handle object of class ' . \Wacon\Feuserregistration\Domain\Model\User::class . ', '
                . $className . ' given instead.';
            $this->addError($errorString, time());
        }

        if (!$this->registerEmailValidationService->isValid($value)) {
            $propertiesWithError = $this->registerEmailValidationService->getPropertiesWithError();
            
            foreach($propertiesWithError as $propertyWithError) {
                $this->addErrorForProperty($propertyWithError['name'], $propertyWithError['errorString'], $propertyWithError['errorCode']);
            }
        }

        // check if user already exists with that email

    }
}