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

use TYPO3\CMS\Core\Http\UploadedFile;
use TYPO3\CMS\Extbase\Utility\LocalizationUtility;
use TYPO3\CMS\Extbase\Validation\Validator\AbstractValidator;

class UploadFeUserForLuxletterValidator extends AbstractValidator
{
    /**
     * @var \Wacon\Feuserregistration\Domain\Service\Validation\RegisterValidationService
     */
    protected $registerValidationService;

    protected function isValid($value): void
    {
        /**
         * @var array
         */
        $upload = $value;

        if (!isset($upload['usergroups']) || empty($upload['usergroups'])) {
            $errorString = LocalizationUtility::translate(
                'module.importFeUserForLuxletter.validator.noUsergroups',
                'feuserregistration'
            );
            $this->addErrorForProperty('usergroups', $errorString, time());
            return;
        }

        if (!isset($upload['importFile']) || get_class($upload['importFile']) !== UploadedFile::class) {
            $errorString = LocalizationUtility::translate(
                'module.importFeUserForLuxletter.validator.file.missing',
                'feuserregistration'
            );
            $this->addErrorForProperty('importFile', $errorString, time());
            return;
        }

        /**
         * @var UploadedFile
         */
        $importFile = $upload['importFile'];

        if ($importFile->getError() !== UPLOAD_ERR_OK) {
            $errorString = LocalizationUtility::translate(
                'module.importFeUserForLuxletter.validator.file.uploadError',
                'feuserregistration'
            );
            $this->addErrorForProperty('importFile', $errorString, time());
            return;
        }

        if ($importFile->getClientMediaType() !== 'text/csv'
            && $importFile->getClientMediaType() !== 'application/vnd.ms-excel'
            && $importFile->getClientMediaType() !== 'application/csv'
            && $importFile->getClientMediaType() !== 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ) {
            $errorString = LocalizationUtility::translate(
                'module.importFeUserForLuxletter.validator.file.invalidFileType',
                'feuserregistration'
            );
            $this->addErrorForProperty('importFile', $errorString, time());
            return;
        }
    }
}
