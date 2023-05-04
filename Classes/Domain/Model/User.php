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

namespace Wacon\Feuserregistration\Domain\Model;

use Wacon\Feuserregistration\Utility\PasswordUtility;

class User extends BaseEntity {
    /**
     * @var string
     */
    protected $email;

    /**
     * @var string
     */
    protected $doiHash;

    /**
     * @var string
     */
    protected $username;

    /**
     * @var string
     */
    protected $password;

    /**
     * @var bool
     */
    protected $disable;

    /**
     * @var string
     */
    protected $usergroup;

    /**
     * Get the value of email
     *
     * @return  string
     */ 
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set the value of email
     *
     * @param  string  $email
     *
     * @return  self
     */ 
    public function setEmail(string $email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get the value of doiHash
     *
     * @return  string
     */ 
    public function getDoiHash()
    {
        return $this->doiHash;
    }

    /**
     * Set the value of doiHash
     *
     * @param  string  $doiHash
     *
     * @return  self
     */ 
    public function setDoiHash(string $doiHash)
    {
        $this->doiHash = $doiHash;

        return $this;
    }

    /**
     * Get the value of username
     *
     * @return  string
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @param  string  $username
     *
     * @return  self
     */ 
    public function setUsername(string $username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get the value of password
     *
     * @return  string
     */ 
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set the value of password
     *
     * @param  string  $password
     *
     * @return  self
     */ 
    public function setPassword(string $password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Set a random password
     * @return string
     */
    public function setRandomPassword() {
        $this->password = PasswordUtility::randomHash();
    }

    /**
     * Get the value of disable
     *
     * @return  bool
     */ 
    public function getDisable()
    {
        return $this->disable;
    }

    /**
     * Set the value of disable
     *
     * @param  bool  $disable
     *
     * @return  self
     */ 
    public function setDisable(bool $disable)
    {
        $this->disable = $disable;

        return $this;
    }

    /**
     * Get the value of usergroup
     *
     * @return  string
     */ 
    public function getUsegroup()
    {
        return $this->usergroup;
    }

    /**
     * Set the value of usergroup
     *
     * @param  string  $usergroup
     *
     * @return  self
     */ 
    public function setUsergroup(string $usergroup)
    {
        $this->usergroup = $usergroup;

        return $this;
    }

    /**
     * Add a usergroup
     * @param string $newUsergroup
     * @return self
     */
    public function addFeGroup(string $newUsergroup) {
        if (!empty($this->usergroup)) {
            $this->usergroup .= ',';
        }

        $this->usergroup = $newUsergroup;

        return $this;
    }
}

