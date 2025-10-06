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

class UsergroupQueryBuilder
{
    public function __construct(
        private readonly ConnectionPool $connectionPool,
    ) {}

    /**
     * Gets all usergroups from the database for Luxletter
     * @return Result
     */
    public function findAllLuxLetterGroups(): Result
    {
        $queryBuilder = $this->connectionPool->getQueryBuilderForTable('fe_groups');
        return $queryBuilder
            ->select('*')
            ->from('fe_groups')
            ->where(
                $queryBuilder->expr()->eq('luxletter_receiver', $queryBuilder->createNamedParameter(1, Connection::PARAM_INT))
            )
            ->orderBy('title', 'ASC')
            ->executeQuery();
    }
}
