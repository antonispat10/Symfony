<?php
/**
 * Created by PhpStorm.
 * User: Antonis
 * Date: 6/21/2018
 * Time: 11:07 PM
 */

namespace App\Twig;


use App\Entity\MicroPost;
use App\Entity\User;
use App\Repository\MicroPostRepository;
use App\Security\RedisStorage;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $locale;
    /**
     * @var RedisStorage
     */
    private $redisStorage;

    public function __construct(string $locale,MicroPostRepository $microPostRepository,RedisStorage $redisStorage)
    {
        $this->locale = $locale;
        $this->microPostRepository = $microPostRepository;
        $this->redisStorage = $redisStorage;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('price',[$this,'priceFilter'])
        ];
    }

    public function getGlobals()
    {        $all_posts =  $this->microPostRepository->findAll();

        return array(
            'all_posts' => $all_posts,
            'locale' => $this->locale
        );
    }

    public function getAllPosts()
    {


    }

    public function priceFilter($number)
    {
        return '$'.number_format($number,2,'.',',');
    }


    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('isFollower', [$this,'isFollower'])
        );
    }

    public function isFollower(User $currentUser, User $isfollowing)
    {

      return $this->redisStorage->isFollower($currentUser,$isfollowing);
    }





}