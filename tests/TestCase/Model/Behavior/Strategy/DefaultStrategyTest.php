<?php
namespace Muffin\Crypt\Test\TestCase\Model\Behavior\Strategy;

use Cake\TestSuite\TestCase;
use Muffin\Crypt\Model\Behavior\Strategy\DefaultStrategy;

class DefaultStrategyTest extends TestCase
{
    public function testEncryptDecrypt()
    {
        $strategy = new DefaultStrategy(getenv('CRYPT_TEST_SALT'));
        $result = $strategy->encrypt('foo');
        $this->assertNotEquals('foo', $result);

        $result = $strategy->decrypt($result);
        $this->assertEquals('foo', $result);
    }
}
