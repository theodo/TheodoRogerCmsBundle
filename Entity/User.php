<?php

namespace Theodo\RogerCmsBundle\Entity;

use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\True;
use Symfony\Component\Validator\Constraints\Min;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

use Theodo\RogerCmsBundle\Repository\UserRepository;

/**
 * Theodo\RogerCmsBundle\Entity\User
 */
class User implements UserInterface
{
    /**
     * @var integer $id
     */
    private $id;

    /**
     * @var string $name
     */
    private $name;

    /**
     * @var string $username
     */
    private $username;

    /**
     * @var string $password
     */
    private $password;

    /**
     * @var string $salt
     */
    private $salt;

    /**
     * @var string $email
     */
    private $email;

    /**
     * @var string $language
     */
    private $language;

    /**
     * @var text $notes
     */
    private $notes;

    /**
     * @var boolean $is_main_admin
     */
    private $is_main_admin;

    /**
     * @var Theodo\RogerCmsBundle\Entity\Role
     */
    private $user_roles;

    /**
     * @var datetime $created_at
     */
    private $created_at;

    /**
     * @var datetime $updated_at
     */
    private $updated_at;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->user_roles = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Get id
     *
     * @return integer $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string $name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set username
     *
     * @param string $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Get username
     *
     * @return string $username
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set salt
     *
     * @param string $salt
     */
    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    /**
     * Get salt
     *
     * @return string $salt
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string $email
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set language
     *
     * @param string $language
     */
    public function setLanguage($language)
    {
        $this->language = $language;
    }

    /**
     * Get language
     *
     * @return string $language
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set notes
     *
     * @param text $notes
     */
    public function setNotes($notes)
    {
        $this->notes = $notes;
    }

    /**
     * Get notes
     *
     * @return text $notes
     */
    public function getNotes()
    {
        return $this->notes;
    }

    /**
     * Set is_main_admin
     *
     * @param boolean $isMainAdmin
     */
    public function setIsMainAdmin($isMainAdmin)
    {
        $this->is_main_admin = $isMainAdmin;
    }

    /**
     * Get is_main_admin
     *
     * @return boolean $isMainAdmin
     */
    public function getIsMainAdmin()
    {
        return $this->is_main_admin;
    }

    /**
     * Add user_roles
     *
     * @param Theodo\RogerCmsBundle\Entity\Role $userRoles
     */
    public function addUserRoles(\Theodo\RogerCmsBundle\Entity\Role $userRoles)
    {
        $this->user_roles[] = $userRoles;
    }

    /**
     * Get user_roles
     *
     * @return Doctrine\Common\Collections\Collection $userRoles
     */
    public function getUserRoles()
    {
        return $this->user_roles;
    }

    /**
     * Erases the user credentials.
     */
    public function eraseCredentials()
    {

    }

    /**
     * Gets an array of roles.
     *
     * @return array An array of Role objects
     */
    public function getRoles()
    {
        return $this->getUserRoles()->toArray();
    }

    /**
     * Compares this user to another to determine if they are the same.
     *
     * @param UserInterface $user The user
     * @return boolean True if equal, false othwerwise.
     */
    public function equals(UserInterface $user)
    {
        return $this->getId() == $user->getId();
    }

    /**
     * Return gravatar hash of user email
     *
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     */
    public function getGravatarEmailHash()
    {
       return md5(strtolower(trim($this->getEmail())));
    }

    private $password_confirm;

    /**
     * Set password
     *
     * @param string $password
     */
    public function setPasswordConfirm($password_confirm)
    {
        $this->password_confirm = $password_confirm;
    }

    /**
     * Get password
     *
     * @return string $password
     */
    public function getPasswordConfirm()
    {
        return $this->password_confirm;
    }

    /**
     * Check password
     *
     * @return boolean
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since date
     */
    public function isValidPassword()
    {
        return $this->getPasswordConfirm() === $this->getPassword();
    }

    /**
     * Check not empty password
     *
     * @return boolean
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function isNotEmptyPassword()
    {
        // Object is new ?
        if (!$this->getId() && $this->isValidPassword())
        {
            return $this->getPasswordConfirm() && $this->getPassword();
        }

        return true;
    }

    /**
     * Check roles
     *
     * @return boolean
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-28
     */
    public function isValidRole()
    {
        return count($this->getRoles());
    }

    /**
     * User validator
     *
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06-27
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        // Name validator: not null
        $metadata->addPropertyConstraint('name', new NotBlank());

        // Username validator: not null and unique
        $metadata->addPropertyConstraint('username', new NotBlank());
        $metadata->addConstraint(new UniqueEntity(array('fields' => array('username'))));

        // Email validator: not null and unique
        $metadata->addPropertyConstraint('email', new NotBlank());
        $metadata->addPropertyConstraint('email', new Email());
        $metadata->addConstraint(new UniqueEntity(array('fields' => array('email'))));

        // Password validator : not blank and match to confirmation
        //$metadata->addGetterConstraint('validPassword', new True(array('message' => 'The password does not match to confirmation')));
        //$metadata->addGetterConstraint('notEmptyPassword', new True(array('message' => 'The password can not be empty')));

        // Role validator
        $metadata->addGetterConstraint('validRole', new Min(array('limit' => 1, 'message' => 'The user must have at least one role')));

        // Language validator: available
        $metadata->addPropertyConstraint('language', new Choice(array('choices' => array('' => '') + array_keys(UserRepository::getAvailableLanguages()))));
    }

    /**
     * Set created_at
     *
     * @param datetime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->created_at = $createdAt;
    }

    /**
     * Get created_at
     *
     * @return datetime
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * Set updated_at
     *
     * @param datetime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updated_at = $updatedAt;
    }

    /**
     * Get updated_at
     *
     * @return datetime
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

    /**
     * Add user_roles
     *
     * @param Theodo\RogerCmsBundle\Entity\Role $userRoles
     */
    public function addRole(\Theodo\RogerCmsBundle\Entity\Role $userRoles)
    {
        $this->user_roles[] = $userRoles;
    }
}