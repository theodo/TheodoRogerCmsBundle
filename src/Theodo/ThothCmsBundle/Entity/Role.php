<?php

namespace Theodo\ThothCmsBundle\Entity;

use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Theodo\ThothCmsBundle\Entity\Role
 */
class Role implements RoleInterface
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
     * Implementation of getRole for the RoleInterface.
     * 
     * @return string The role.
     */
    public function getRole()
    {
        return $this->getName();
    }

    /**
     * toString function
     * 
     * @return string
     * @author Vincent Guillon <vincentg@theodo.fr>
     * @since 2011-06)27
     */
    public function __toString()
    {
        $available_role_names = \Theodo\ThothCmsBundle\Repository\RoleRepository::getAvailableRoles();
        
        return $available_role_names[$this->getName()];
    }
}