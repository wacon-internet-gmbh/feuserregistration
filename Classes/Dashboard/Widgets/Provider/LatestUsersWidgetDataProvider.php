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

namespace Wacon\Feuserregistration\Dashboard\Widgets\Provider;

use TYPO3\CMS\Dashboard\Widgets\ListDataProviderInterface;
use TYPO3\CMS\Dashboard\Widgets\NumberWithIconDataProviderInterface;
use Wacon\Feuserregistration\Domain\QueryBuilder\UserQueryBuilder;

class LatestUsersWidgetDataProvider implements ListDataProviderInterface, NumberWithIconDataProviderInterface
{
    /**
     * Total number of items
     * @var int
     */
    protected int $number = 0;

    /**
     * Items to be displayed
     * @var array
     */
    protected array $items = [];

    /**
     * Summary of initialized
     * @var bool
     */
    private bool $initialized = false;

    public function __construct(
        private readonly UserQueryBuilder $userQueryBuilder
    ) {}

    protected function initialize(): void
    {
        $items = $this->userQueryBuilder->getLatestCreatedFrontendUsers(5);

        foreach ($items as $item) {
            $crdate = new \DateTime();
            $crdate->setTimestamp((int)$item['crdate']);
            $this->items[] = $item['username'] . ' (' . $crdate->format('Y-m-d H:i') . ')';
        }

        $this->number = count($this->items);
        $this->initialized = true;
    }

    /**
     * Summary of getItems
     * @return array<string|int, mixed>
     */
    public function getItems(): array
    {
        if (!$this->initialized) {
            $this->initialize();
        }
        return $this->items;
    }

    /**
     * Summary of getNumber
     * @return int
     */
    public function getNumber(): int
    {
        if (!$this->initialized) {
            $this->initialize();
        }
        return $this->number;
    }
}
