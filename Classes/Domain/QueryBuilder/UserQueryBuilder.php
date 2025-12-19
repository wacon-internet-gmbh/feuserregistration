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

namespace Wacon\Feuserregistration\Domain\QueryBuilder;

use Doctrine\DBAL\Result;
use TYPO3\CMS\Core\Database\Connection;
use TYPO3\CMS\Core\Database\ConnectionPool;

class UserQueryBuilder
{
    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {}

    /**
     * Deletes all frontend users in the specified page
     * @param int $pageId
     * @return Result
     */
    public function deleteAllFrontendUsersInPage(int $pageId): Result
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('fe_users');
        return $queryBuilder
            ->delete('fe_users')
            ->where(
                $queryBuilder->expr()->eq('pid', $queryBuilder->createNamedParameter($pageId, Connection::PARAM_INT))
            )
            ->executeQuery();
    }
}
