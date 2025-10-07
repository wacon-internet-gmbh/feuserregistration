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

namespace Wacon\Feuserregistration\FileReader;

use Shuchkin\SimpleXLSX;
use TYPO3\CMS\Core\Http\UploadedFile;

class CsvAndXlsxReader
{
    /**
     * Summary of options
     * @var array
     */
    protected array $options = [
        'separator' => ',',
    ];

    /**
     * Parse CSV or XLSX file and return array of lines
     * @param UploadedFile $fileUpload
     * @param array $options
     * @return array|bool
     */
    public function parseFile(UploadedFile $fileUpload, array $options = []): array
    {
        $this->options = array_merge($this->options, $options);
        $fileExtension = \pathinfo($fileUpload->getClientFilename(), PATHINFO_EXTENSION);
        $data = [];

        if ($fileExtension === 'csv') {
            $lines = $this->parseCsvFile($fileUpload);
        } elseif ($fileExtension === 'xlsx') {
            $lines = $this->parseXlsxFile($fileUpload);
        } else {
            return [];
        }

        return $lines;
    }

    /**
     * Parse CSV file and return array of lines
     * @param UploadedFile $fileUpload
     * @return array|bool
     */
    public function parseCsvFile(UploadedFile $fileUpload): array
    {
        $lines = explode("\n", trim($fileUpload->getStream()->getContents()));
        $csv = [];

        foreach ($lines as $line) {
            $row = \str_getcsv($line, $this->options['separator']);
            $csv[] = $row;
        }

        return $csv;
    }

    /**
     * Parse XLSX file and return array of lines
     * @param \TYPO3\CMS\Core\Http\UploadedFile $fileUpload
     * @return array
     */
    public function parseXlsxFile(UploadedFile $fileUpload): array
    {
        $xlsx = SimpleXLSX::parse($fileUpload->getTemporaryFileName());
        if ($xlsx === false) {
            return [];
        }
        return $xlsx->rows();
    }
}
