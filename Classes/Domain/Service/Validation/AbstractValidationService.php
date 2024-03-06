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


namespace Wacon\Feuserregistration\Domain\Service\Validation;

abstract class AbstractValidationService {
    /**
     * @var string
     */
    protected $extensionName = 'feuserregistration';

    /**
     * Assoc of all properties with error including
     * error message and code
     * @var array
     */
    protected $propertiesWithError = [];    
    
    /**
     * Check if object is valid
     * @param mixed $value
     * @return bool
     */
    abstract public function isValid($value);

    /**
     * Return $propertiesWithError
     * @return array
     */
    public function getPropertiesWithError() {
        return $this->propertiesWithError;
    }

    /**
     * Reset the object
     * @return void
     */
    public function reset() {
        $this->propertiesWithError = [];
    }
}