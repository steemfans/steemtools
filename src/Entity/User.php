<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 */
class User
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=150)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=32, nullable=true)
     */
    private $wx_openid;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $wx_userinfo;

    /**
     * @ORM\Column(type="string", length=200, nullable=true)
     */
    private $username;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $setting;

    /**
     * @ORM\Column(type="string", length=8, nullable=true)
     */
    private $authcode;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $expired_at;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $last_mentioned_at;

    /**
     * @ORM\Column(type="integer")
     */
    private $created_at;

    public function __construct()
    {
        $this->setCreatedAt(time());
        $this->setLastMentionedAt(time());
    }
 
    /**
    * Gets the value of id.
    *
    * @return mixed
    */
    public function getId()
    {
        return $this->id;
    }
 
    /**
    * Sets the value of id.
    *
    * @param mixed $id the id
    *
    * @return self
    */
    public function setId($id)
    {
        $this->id = $id;
    }
 
    /**
    * Gets the value of email.
    *
    * @return mixed
    */
    public function getEmail()
    {
        return $this->email;
    }
 
    /**
    * Sets the value of email.
    *
    * @param mixed $email the email
    *
    * @return self
    */
    public function setEmail($email)
    {
        $this->email = $email;
    }
 
    /**
    * Gets the value of username.
    *
    * @return mixed
    */
    public function getUsername()
    {
        return $this->username;
    }
 
    /**
    * Sets the value of username.
    *
    * @param mixed $username the username
    *
    * @return self
    */
    public function setUsername($username)
    {
        $this->username = $username;
    }
 
    /**
    * Gets the value of authcode.
    *
    * @return mixed
    */
    public function getAuthcode()
    {
        return $this->authcode;
    }
 
    /**
    * Sets the value of authcode.
    *
    * @param mixed $authcode the authcode
    *
    * @return self
    */
    public function setAuthcode($authcode)
    {
        $this->authcode = $authcode;
    }
 
    /**
    * Gets the value of expired_at.
    *
    * @return mixed
    */
    public function getExpiredAt()
    {
        return $this->expired_at;
    }
 
    /**
    * Sets the value of expired_at.
    *
    * @param mixed $expired_at the expired at
    *
    * @return self
    */
    public function setExpiredAt($expired_at)
    {
        $this->expired_at = $expired_at;
    }
 
    /**
    * Gets the value of created_at.
    *
    * @return mixed
    */
    public function getCreatedAt()
    {
        return $this->created_at;
    }
 
    /**
    * Sets the value of created_at.
    *
    * @param mixed $created_at the created at
    *
    * @return self
    */
    public function setCreatedAt($created_at)
    {
        $this->created_at = $created_at;
    }
 
    /**
    * Gets the value of setting.
    *
    * @return mixed
    */
    public function getSetting()
    {
        return $this->setting;
    }
 
    /**
    * Sets the value of setting.
    *
    * @param mixed $setting the setting
    *
    * @return self
    */
    public function setSetting($setting)
    {
        $this->setting = $setting;
    }
 
    /**
    * Gets the value of last_mentioned_at.
    *
    * @return mixed
    */
    public function getLastMentionedAt()
    {
        return $this->last_mentioned_at;
    }
 
    /**
    * Sets the value of last_mentioned_at.
    *
    * @param mixed $last_mentioned_at the last mentioned at
    *
    * @return self
    */
    public function setLastMentionedAt($last_mentioned_at)
    {
        $this->last_mentioned_at = $last_mentioned_at;
    }
}
