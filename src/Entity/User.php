<?php

namespace App\Entity;

use App\Security\RedisStorage;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Serializable;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(fields="email",message="This email is already used")
 * @UniqueEntity(fields="username", message="This username is already used")
 */
class User implements UserInterface , \Serializable
{
    const ROLE_USER = 'ROLE_USER';
    const ROLE_ADMIN = 'ROLE_ADMIN';
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var RedisStorage
     */
    private $redisStorage;

    /**
     * User constructor.
     * @param $posts
     */
    public function __construct(RedisStorage $redisStorage)
    {
        $this->posts = new ArrayCollection();
        $this->postsLiked = new ArrayCollection();
        $this->roles = [self::ROLE_USER];
        $this->enabled = false;

        $this->redisStorage = $redisStorage;
    }

    /**
     * @return mixed
     */
    public function getPosts()
    {
        return $this->posts;
    }

    public function getId()
    {
        return $this->id;
    }

    /**
     * @ORM\Column(type="string",length=50,unique=true)
     * @Assert\NotBlank()
     * @Assert\Length(min=5,max=50)
     */
    private $username;

    /**
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=8, max=4096)
     */
    private $plainPassword;

    /**
     * @return mixed
     */
    public function getFullName()
    {
        return $this->fullName;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword): void
    {
        $this->plainPassword = $plainPassword;
    }


    /**
     * @ORM\Column(type="string",length=54,unique=true)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\MicroPost", mappedBy="user")
     */
    private $posts;


    /**
     * @ORM\Column(type="string", nullable=true,length=30)
     */
    private $confirmationToken;

    /**
     * @return UserPreferences|null
     */
    public function getPreferences()
    {
        return $this->preferences;
    }

    /**
     * @param mixed $preferences
     */
    public function setPreferences($preferences): void
    {
        $this->preferences = $preferences;
    }

    /**
     * @ORM\Column(type="boolean")
     */
    private $enabled;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserPreferences",cascade={"persist"})
     */
    private $preferences;

    /**
     * @ORM\Column(type="string",length=50)
     * @Assert\NotBlank()
     * @Assert\Length(min=4,max=50)
     */
    private $fullName;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\MicroPost", mappedBy="likedBy")
     */
    private $postsLiked;

    public function serialize()
    {
        return Serialize([
            $this->id,
            $this->username,
            $this->password
        ]);
            }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
        $this->username = $username;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
        $this->password = $password;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
        $this->email = $email;
    }

    /**
     * @param mixed $fullName
     */
    public function setFullName($fullName): void
    {
        $this->fullName = $fullName;
    }

    public function unserialize($serialized)
    {
        list($this->id,
            $this->username,
            $this->password) = unserialize($serialized);
    }

    /**
     * @var array
     * @ORM\Column(type="simple_array");
     */
  private $roles;

    public function getRoles()
{
    return $this->roles;
}

    public function setRoles(array $roles) :void
    {
        $this->roles = $roles;
    }


    public function getPassword()
{
    return $this->password;
}/**
 * Returns the salt that was originally used to encode the password.
 *
 * This can return null if the password was not encoded using a salt.
 *
 * @return string|null The salt
 */public function getSalt()
{
    return null;
}/**
 * Returns the username used to authenticate the user.
 *
 * @return string The username
 */public function getUsername()
{
    return $this->username;
}/**
 * Removes sensitive data from the user.
 *
 * This is important if, at any given point, sensitive information like
 * the plain-text password is stored on this object.
 */public function eraseCredentials()
{
    // TODO: Implement eraseCredentials() method.
}

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @return Collection
     */
    public function getPostsLiked()
    {
        return $this->postsLiked;
    }

    /**
     * @return mixed
     */
    public function getConfirmationToken()
    {
        return $this->confirmationToken;
    }

    /**
     * @param mixed $confirmationToken
     */
    public function setConfirmationToken($confirmationToken): void
    {
        $this->confirmationToken = $confirmationToken;
    }

    /**
     * @return mixed
     */
    public function getEnabled()
    {
        return $this->enabled;
    }

    /**
     * @param mixed $enabled
     */
    public function setEnabled($enabled): void
    {
        $this->enabled = $enabled;
    }


    public function isAccountNonExpired(){
        return true;
    }


    public function isAccountNonLocked(){
        return true;
    }

    public function isCredentialsNonExpired(){
        return true;

    }


    public function isEnabled(){
        return true;
    }

}
