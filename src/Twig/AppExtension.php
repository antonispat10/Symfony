<?php
/**
 * Created by PhpStorm.
 * User: Antonis
 * Date: 6/21/2018
 * Time: 11:07 PM
 */

namespace App\Twig;


use App\Entity\MicroPost;
use App\Repository\MicroPostRepository;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension implements GlobalsInterface
{
    private $locale;
    public function __construct(string $locale,MicroPostRepository $microPostRepository)
    {
        $this->locale = $locale;
        $this->microPostRepository = $microPostRepository;
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
            'session'   => $_SESSION,
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




}