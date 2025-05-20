<?php
declare(strict_types=1);

namespace Wacon\Feuserregistration\Controller;

use Psr\Http\Message\ResponseInterface;
use TYPO3\CMS\Core\Utility\GeneralUtility;
use Wacon\Feuserregistration\Utility\TextToSpeechUtility;

/*
 * This file is part of the TYPO3 extension feuserregistration.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Kevin Chileong Lee, info@wacon.de, WACON Internet GmbH
 */

/**
 * CaptchaController
 */
class CaptchaController extends BaseActionController
{
    /**
     * Create and return a math image
     * @param bool $returnCode
     * @return ResponseInterface
     */
    public function mathImageAction():ResponseInterface
    {
        $postArguments = json_decode($this->request->getBody()->getContents(), true);
        if (is_array($postArguments) && array_key_exists('returnCode', $postArguments) && $postArguments['returnCode'] === true) {
            $frontendUser = $this->request->getAttribute('frontend.user');
            $formular = $frontendUser->getKey('ses', self::class . '->mathImage->formular');

            return $this->responseFactory
                ->createResponse()
                ->withHeader('Content-Type', 'application/json')
                ->withBody(
                    $this->streamFactory->createStream(
                        json_encode(['formular' => TextToSpeechUtility::formatMathFormula($formular, $this->extensionName)])
                    )
                );
        }

        $operators = ['+', '-'];
        $operatorsCount = count($operators);
        $amountOfOperators = rand(1, $operatorsCount);
        $formula = '';

        for($i = 0; $i < $amountOfOperators; $i++) {
            $currentOperator = (string)$operators[rand(0, $operatorsCount-1)];
            $num1=rand(1, 10);
            $num2=rand(1, 10);
            $formula .= (string)$num1 . ' ' . $currentOperator . ' ' . (string)$num2;

            if (($i+1) < $amountOfOperators) {
                $formula .= ' ' . (string)$operators[rand(0, $operatorsCount-1)] . ' ';
            }
        }

        $formularAsArray = GeneralUtility::trimExplode(' ', $formula);

        $lastOperator = false;
        $mathResult = 0;

        foreach($formularAsArray as $value) {
            if (in_array($value, $operators)) {
                $lastOperator = $value;
            }elseif($lastOperator) {
                switch($lastOperator) {
                    case '+':
                        $mathResult = $mathResult + $value;
                        break;
                    case '-':
                        $mathResult = $mathResult - $value;
                        break;
                }
            }else {
                $mathResult = $value;
            }
        }

        $frontendUser = $this->request->getAttribute('frontend.user');
        $frontendUser->setKey('ses', self::class . '->mathImage', $mathResult);
        $frontendUser->setKey('ses', self::class . '->mathImage->formular', $formula);
        $frontendUser->storeSessionData();

        $font = GeneralUtility::getFileAbsFileName('EXT:' . $this->extensionName . '/Resources/Public/Fonts/ReenieBeanie/ReenieBeanie-Regular.ttf');
        $image = imagecreatetruecolor(strlen($formula) * 20, 30); //Change the numbers to adjust the size of the image
        $color = imagecolorallocate($image, 0, 100, 90);
        $white = imagecolorallocate($image, 0, 26, 26);

        imagefilledrectangle($image,0,0,399,99,$white);
        imagettftext ($image, 30, 0, 20, 25, $color, $font, $formula );//Change the numbers to adjust the font-size

        imagepng($image);

        return $this->responseFactory->createResponse()->withHeader('Content-Type', 'image/png');
    }
}
