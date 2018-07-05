<?php

namespace App\Service;


use Psr\Log\LoggerInterface;

class Greeting
{

    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }


    public function greet( $name)
    {
        $this->logger->info("greeted $name");
        return "Hellooo $name";
    }




}