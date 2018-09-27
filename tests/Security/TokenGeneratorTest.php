<?php
/**
 * Created by PhpStorm.
 * User: Antonis
 * Date: 7/5/2018
 * Time: 8:17 PM
 */

namespace App\Tests\Security;

use PHPUnit\Framework\TestCase;
use App\Security\TokenGenerator;

class TokenGeneratorTest extends TestCase
{
    public function testTokenGeneration()
    {

        $tokenGen = new TokenGenerator();
        $token = $tokenGen->getRandomSecureToken(30);

        $this->assertEquals(30,strlen($token));
        $this->assertTrue(ctype_alumn($token),'Token contains incorrect characters');
    }

}