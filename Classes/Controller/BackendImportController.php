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

namespace Wacon\Feuserregistration\Controller;

use Psr\Http\Message\ResponseInterface;
use SJBR\SrFeuserRegister\Utility\LocalizationUtility;
use TYPO3\CMS\Backend\Attribute\AsController;
use TYPO3\CMS\Backend\Template\ModuleTemplateFactory;
use TYPO3\CMS\Core\Configuration\ExtensionConfiguration;
use TYPO3\CMS\Core\Domain\Repository\PageRepository;
use TYPO3\CMS\Core\Page\AssetCollector;
use TYPO3\CMS\Core\Type\ContextualFeedbackSeverity;
use TYPO3\CMS\Extbase\Annotation\Validate;
use TYPO3\CMS\Extbase\Mvc\Controller\ActionController;
use Wacon\Feuserregistration\Domain\Model\User;
use Wacon\Feuserregistration\Domain\QueryBuilder\UsergroupQueryBuilder;
use Wacon\Feuserregistration\Domain\QueryBuilder\UserQueryBuilder;
use Wacon\Feuserregistration\Domain\Repository\UserRepository;
use Wacon\Feuserregistration\Domain\Validator\UploadFeUserForLuxletterValidator;
use Wacon\Feuserregistration\FileReader\CsvAndXlsxReader;
use Wacon\Feuserregistration\Utility\PasswordUtility;
use Wacon\Feuserregistration\Utility\Typo3\Extbase\PersistenceUtility;
use Wacon\Feuserregistration\Utility\ValidationUtility;

#[AsController]
final class BackendImportController extends ActionController
{
    public function __construct(
        protected readonly ModuleTemplateFactory $moduleTemplateFactory,
        protected readonly PageRepository $pageRepository,
        protected readonly UserRepository $userRepository,
        protected readonly UserQueryBuilder $userQueryBuilder,
        protected readonly UsergroupQueryBuilder $usergroupQueryBuilder,
        protected readonly CsvAndXlsxReader $csvAndXlsxReader,
        private readonly AssetCollector $assetCollector,
        private readonly ExtensionConfiguration $extensionConfiguration
    ) {
        PersistenceUtility::disableRespectStoragePage($this->userRepository);
    }

    /**
     * Summary of importFeUserForLuxletterAction
     * @return ResponseInterface
     */
    public function importFeUserForLuxletterAction(): ResponseInterface
    {
        $this->assetCollector->addStyleSheet(
            'feuserregistration-backend',
            'EXT:feuserregistration/Resources/Public/Css/backend.css'
        );
        $moduleTemplate = $this->moduleTemplateFactory->create($this->request);
        $pageId = (int)($this->request->getQueryParams()['id'] ?? 0);
        $usergroups = $this->usergroupQueryBuilder->findAllLuxLetterGroups()->fetchAllAssociative();

        $moduleTemplate->assign('page', $this->pageRepository->getPage($pageId));
        $moduleTemplate->assign('usergroups', $usergroups);
        $moduleTemplate->assign('settings', [
            'import' => $this->extensionConfiguration->get('feuserregistration', 'import') ?? [],
        ]);

        return $moduleTemplate->renderResponse('BackendImport/ImportFeUserForLuxletter');
    }

    /**
     * Summary of uploadFeUserForLuxletterAction
     * @param array $upload
     * @return ResponseInterface
     */
    #[Validate([
        'param' => 'upload',
        'validator' => UploadFeUserForLuxletterValidator::class,
    ])]
    public function uploadFeUserForLuxletterAction(array $upload): ResponseInterface
    {
        $pageId = (int)($this->request->getQueryParams()['id'] ?? 0);
        $lines = $this->csvAndXlsxReader->parseFile($upload['importFile'], ['separator' => $upload['seperator']]);
        $emailColumnIndex = 0;
        $importedRecords = 0;
        $randomPassword = PasswordUtility::randomHash();
        $importedEmails = [];

        if ($upload['truncateDb'] === '1') {
            $this->userQueryBuilder->deleteAllFrontendUsersInPage($pageId);
        }

        foreach ($lines as $row) {
            if ($row[$emailColumnIndex] === '' || $row[$emailColumnIndex] === null) {
                continue;
            }

            if (ValidationUtility::isValidEmail($row[$emailColumnIndex]) === false) {
                $this->addFlashMessage(
                    LocalizationUtility::translate('module.importFeUserForLuxletter.upload_form.flashmessage.error.email', 'feuserregistration', [$row[$emailColumnIndex]]),
                    '',
                    ContextualFeedbackSeverity::ERROR
                );
                continue;
            }

            if (in_array($row[$emailColumnIndex], $importedEmails, true)) {
                continue;
            }

            $user = $this->userRepository->findOneByEmail($row[$emailColumnIndex]);

            if (!$user) {
                $user = new User();
                $user->setEmail($row[$emailColumnIndex]);
                $user->setNewsletterReceive(true);
                $user->setPassword($randomPassword);
                $user->setPrivacy(true);
                $user->setUsername($row[$emailColumnIndex]);
                $user->setPid($pageId);
                $user->setUsergroup(\implode(',', $upload['usergroups']));
                $this->userRepository->add($user);
            } else {
                $user->addUsergroups($upload['usergroups']);
                $this->userRepository->update($user);
            }

            $importedEmails[] = $row[$emailColumnIndex];
            $importedRecords++;
        }

        $this->addFlashMessage(
            LocalizationUtility::translate('module.importFeUserForLuxletter.upload_form.flashmessage.success', 'feuserregistration', [$importedRecords])
        );

        return $this->redirect('importFeUserForLuxletter');
    }
}
