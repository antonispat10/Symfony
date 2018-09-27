<?php

namespace App\Security;

use App\Entity\User;
use Predis\Client;
use Symfony\Component\Security\Csrf\TokenStorage\TokenStorageInterface;

class RedisStorage
{
    const KEY_SUFFIX = '-token';
    /**
     * @var Client
     */
    private $redisClient;
    /**
     * @var TokenStorageInterface
     */
    private $tokenStorage;

    /**
     * @param Client $redisClient
     */
    public function __construct(Client $redisClient,TokenStorageInterface $tokenStorage)
    {
        $this->redisClient = $redisClient;
        $this->tokenStorage = $tokenStorage;
    }


    public function follow(User $currentuser, User $userToFollow): void
    {
        $this->redisClient->set(
            $currentuser->getId().'follow'.$userToFollow->getId(),
            1
        );
    }


    public function unfollow(User $currentuser, User $userToFollow): void
    {
        $this->redisClient->del(
            $currentuser->getId().'follow'.$userToFollow->getId(),
            1
        );
    }

    public function isFollower(User $currentuser, User $userToFollow)
    {
        $isFollower = $this->redisClient->get($currentuser->getId().'follow'.$userToFollow->getId());
        if($isFollower){
            return true;
        }else {
            return false;
        }
    }


    public function userFollowers(User $userWithPosts)
    {
        $list = $this->redisClient->keys($userWithPosts->getId().'follow'.'*');
        if($list){
            foreach($list as $key){

                $values[] = str_replace( $userWithPosts->getId().'follow', '', $key);

            }

            return $values;
        }
        else return;
    }

    public function userFollowing(User $userWithPosts)
    {
        $list = $this->redisClient->keys('*'.'follow'.$userWithPosts->getId());
        if($list){
            foreach($list as $i=>$key){

                $values[$i] = str_replace( 'follow', '', $key);
                $values[$i] = str_replace( $userWithPosts->getId(), '', $values[$i]);

            }
            return $values;
        }
        else return;
    }

    public function followers(User $currentuser)
    {
        $list = $this->redisClient->keys($currentuser->getId().'follow'.'*');
        if($list){
            foreach($list as $key){

          $values[] = str_replace($currentuser->getId().'follow', '', $key);
            }

            return $values;
        }
        else return;
    }
}