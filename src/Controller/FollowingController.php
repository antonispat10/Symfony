<?php
/**
 * Created by PhpStorm.
 * User: Antonis
 * Date: 6/29/2018
 * Time: 5:46 PM
 */

namespace App\Controller;
use App\Entity\User;
use App\Security\RedisStorage;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\Routing\Annotation\Route;


/**
 * @Security("is_granted('ROLE_USER') or is_granted('ROLE_ADMIN') ")
 * @Route("/following")
 */
class FollowingController extends Controller
{

    private $redisStorage;

    public function __construct(
        RedisStorage $redisStorage
    ) {
        $this->redisStorage = $redisStorage;
    }

    /**
     * @Route("/follow/{id}", name="following_follow")
     */
    public function follow(User $userToFollow)
    {


        /** @var User $currentUser */
        $currentUser = $this->getUser();




        if ($userToFollow->getId() !== $currentUser->getId()) {

            $this->redisStorage->follow($currentUser, $userToFollow);


        }

        return $this->redirectToRoute(
            'micro_post_user',
            ['username' => $userToFollow->getUsername()]
        );


    }

    /**
     * @Route("/unfollow/{id}", name="following_unfollow")
     */
    public function unfollow(User $userToUnfollow)
    {

        /** @var User $currentUser */
        $currentUser = $this->getUser();

        $this->redisStorage->unfollow($currentUser, $userToUnfollow);



        return $this->redirectToRoute(
            'micro_post_user',
            ['username' => $userToUnfollow->getUsername()]
        );

    }


}