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

use TYPO3\CMS\Core\Utility\GeneralUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class RegisterValidator extends AbstractValidator
{
    /**
     * @var \Wacon\Feuserregistration\Domain\Service\Validation\RegisterValidationService
     */
    protected $registerValidationService;

    protected function isValid($value): void
    {
        $this->registerValidationService = GeneralUtility::makeInstance(\Wacon\Feuserregistration\Domain\Service\Validation\RegisterValidationService::class);
        $className = get_class($value);

        if (($value instanceof \Wacon\Feuserregistration\Domain\Model\User) !== true) {
            $errorString = 'The user validator can only handle object of class ' . \Wacon\Feuserregistration\Domain\Model\User::class . ', '
                . get_class($value) . ' given instead.';
            $this->addError($errorString, time());
        }

        if (!$this->registerValidationService->isValid($value)) {
            $propertiesWithError = $this->registerValidationService->getPropertiesWithError();

            foreach ($propertiesWithError as $propertyWithError) {
                $this->addErrorForProperty($propertyWithError['name'], $propertyWithError['errorString'], $propertyWithError['errorCode']);
            }
        }
    }
}
