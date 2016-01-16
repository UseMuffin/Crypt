<?php
namespace Muffin\Crypt\Test\TestCase\Utility;

use Muffin\Crypt;

class FunctionsTest extends \PHPUnit_Framework_TestCase
{
    public function testEncryptDecrypt()
    {
        $result = Crypt\public_encrypt('foo', getenv('CRYPT_TEST_PUBLIC_KEY'));
        $this->assertNotEquals('foo', $result);

        $result = Crypt\private_decrypt($result, getenv('CRYPT_TEST_PRIVATE_KEY'));
        $this->assertEquals('foo', $result);
    }
}
