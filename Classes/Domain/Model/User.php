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
     * @var string
     */
    protected $firstName;

    /**
     * @var string
     */
    protected $lastName;

    /**
     * @var string
     */
    protected $address;

    /**
     * @var string
     */
    protected $zip;

    /**
     * @var string
     */
    protected $city;

    /**
     * @var string
     */
    protected $country;

    /**
     * @var string
     */
    protected $phone;

    /**
     * @var string
     */
    protected $fax;

    /**
     * @var string
     */
    protected $www;

    /**
     * @var bool
     */
    protected $newsletterReceive;

    /**
     * @var string
     */
    protected $luxletterLanguage;

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

    /**
     * Get the value of firstName
     *
     * @return  string
     */ 
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * Set the value of firstName
     *
     * @param  string  $firstName
     *
     * @return  self
     */ 
    public function setFirstName(string $firstName)
    {
        $this->firstName = $firstName;

        return $this;
    }

    /**
     * Get the value of lastName
     *
     * @return  string
     */ 
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * Set the value of lastName
     *
     * @param  string  $lastName
     *
     * @return  self
     */ 
    public function setLastName(string $lastName)
    {
        $this->lastName = $lastName;

        return $this;
    }

    /**
     * Get the value of address
     *
     * @return  string
     */ 
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set the value of address
     *
     * @param  string  $address
     *
     * @return  self
     */ 
    public function setAddress(string $address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get the value of zip
     *
     * @return  string
     */ 
    public function getZip()
    {
        return $this->zip;
    }

    /**
     * Set the value of zip
     *
     * @param  string  $zip
     *
     * @return  self
     */ 
    public function setZip(string $zip)
    {
        $this->zip = $zip;

        return $this;
    }

    /**
     * Get the value of city
     *
     * @return  string
     */ 
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set the value of city
     *
     * @param  string  $city
     *
     * @return  self
     */ 
    public function setCity(string $city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get the value of country
     *
     * @return  string
     */ 
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set the value of country
     *
     * @param  string  $country
     *
     * @return  self
     */ 
    public function setCountry(string $country)
    {
        $this->country = $country;

        return $this;
    }

    /**
     * Get the value of phone
     *
     * @return  string
     */ 
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set the value of phone
     *
     * @param  string  $phone
     *
     * @return  self
     */ 
    public function setPhone(string $phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get the value of fax
     *
     * @return  string
     */ 
    public function getFax()
    {
        return $this->fax;
    }

    /**
     * Set the value of fax
     *
     * @param  string  $fax
     *
     * @return  self
     */ 
    public function setFax(string $fax)
    {
        $this->fax = $fax;

        return $this;
    }

    /**
     * Get the value of www
     *
     * @return  string
     */ 
    public function getWww()
    {
        return $this->www;
    }

    /**
     * Set the value of www
     *
     * @param  string  $www
     *
     * @return  self
     */ 
    public function setWww(string $www)
    {
        $this->www = $www;

        return $this;
    }

    /**
     * Get the value of newsletterReceive
     *
     * @return  bool
     */ 
    public function getNewsletterReceive()
    {
        return $this->newsletterReceive;
    }

    /**
     * Set the value of newsletterReceive
     *
     * @param  bool  $newsletterReceive
     *
     * @return  self
     */ 
    public function setNewsletterReceive(bool $newsletterReceive)
    {
        $this->newsletterReceive = $newsletterReceive;

        return $this;
    }

    /**
     * Get the value of luxletterLanguage
     *
     * @return  string
     */ 
    public function getLuxletterLanguage()
    {
        return $this->luxletterLanguage;
    }

    /**
     * Set the value of luxletterLanguage
     *
     * @param  string  $luxletterLanguage
     *
     * @return  self
     */ 
    public function setLuxletterLanguage(string $luxletterLanguage)
    {
        $this->luxletterLanguage = $luxletterLanguage;

        return $this;
    }
}

