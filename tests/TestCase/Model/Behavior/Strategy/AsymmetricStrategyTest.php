<?php
namespace Muffin\Crypt\Test\TestCase\Model\Behavior\Strategy;

use Cake\TestSuite\TestCase;
use Muffin\Crypt\Model\Behavior\Strategy\AsymmetricStrategy;

class AsymmetricStrategyTest extends TestCase
{
    public $PrivateStrategy;
    public $PublicStrategy;

    public function setUp()
    {
        $public = getenv('CRYPT_TEST_PUBLIC_KEY');
        $private = getenv('CRYPT_TEST_PRIVATE_KEY');
        $this->PrivateStrategy = new AsymmetricStrategy($public, $private);
        $this->PublicStrategy = new AsymmetricStrategy($public);
    }

    /**
     * @expectedException \Cake\Core\Exception\Exception
     */
    public function testConstructorThrowsExceptionOnInvalidPublicKey()
    {
        new AsymmetricStrategy('');
    }

    /**
     * @expectedException \Cake\Core\Exception\Exception
     */
    public function testConstructorThrowsExceptionOnInvalidPrivateKey()
    {
        new AsymmetricStrategy(getenv('CRYPT_TEST_PUBLIC_KEY'), '');
    }

    public function testEncryptDecrypt()
    {
        $result = $this->PublicStrategy->encrypt('foo');
        $this->assertNotEquals('foo', $result);

        $result = $this->PrivateStrategy->decrypt($result);
        $this->assertEquals('foo', $result);
    }
}
