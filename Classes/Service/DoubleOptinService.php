<?php
declare(strict_types=1);

/*
 * This file is part of the TYPO3 extension t3templates_base.
 *
 * For the full copyright and license information, please read the
 * LICENSE.txt file that was distributed with this source code.
 *
 *  (c) 2023 Kevin Chileong Lee, info@wacon.de, WACON Internet GmbH
 */

 namespace Wacon\Feuserregistration\Service;
 
 use Wacon\Feuserregistration\Domain\Model\User;

 class DoubleOptinService {
    /**
     * @var User
     */
    protected $user;

    /**
     * @var string
     */
    protected $hash;

    public function sendMail(User $user) {
        $this->hash = md5(uniqid((string)time()));
        $this->user = $user;
        
        
        return $this->hash;
    }
 }