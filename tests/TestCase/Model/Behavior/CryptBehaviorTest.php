<?php
namespace Muffin\Crypt\Test\TestCase\Model\Behavior;

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Muffin\Crypt\Model\Behavior\CryptBehavior;
use Muffin\Crypt\Model\Behavior\Strategy\AssymetricStrategy;
use Muffin\Crypt\Model\Behavior\Strategy\DefaultStrategy;

class CryptBehaviorTest extends TestCase
{

    public $fixtures = [
        'plugin.Muffin/Crypt.CreditCards',
    ];

    public $Behavior;

    /**
     * @var \Cake\ORM\Table
     */
    public $Table;

    public function setUp()
    {
        parent::setUp();

        $table = TableRegistry::get('Muffin/Crypt.CreditCards', ['table' => 'crypt_credit_cards']);
        $table->addBehavior('Muffin/Crypt.Crypt', [
            'fields' => 'number',
            'strategy' => new DefaultStrategy(getenv('CRYPT_TEST_SALT')),
        ]);

        $this->Table = $table;
        $this->Behavior = $table->behaviors()->Crypt;
    }

    public function tearDown()
    {
        parent::tearDown();
        TableRegistry::clear();
    }

    public function testInitialize()
    {
        $result = $this->Behavior->config();
        $expected = ['number' => 'string'];
        $this->assertInstanceOf('Muffin\Crypt\Model\Behavior\Strategy\StrategyInterface', $result['strategy']);
        $this->assertEquals($expected, $result['fields']);

        $this->Table->removeBehavior('Crypt');
        $strategy = new DefaultStrategy(Configure::read('Security.salt'));
        $fields = $expected;
        $this->Table->addBehavior('Muffin/Crypt.Crypt', compact('strategy', 'fields'));

        $this->assertInstanceOf('Muffin\Crypt\Model\Behavior\Strategy\StrategyInterface', $result['strategy']);
        $this->assertEquals($expected, $result['fields']);

        $this->Table->removeBehavior('Crypt');
        $this->Table->addBehavior('Muffin/Crypt.Crypt', ['fields' => ['number']]);

        $this->assertInstanceOf('Muffin\Crypt\Model\Behavior\Strategy\StrategyInterface', $result['strategy']);
        $this->assertEquals($expected, $result['fields']);
    }

    /**
     * @expectedException \Cake\Core\Exception\Exception
     */
    public function testInitializeThrowsExceptionOnEmptyFields()
    {
        $this->Table->removeBehavior('Crypt');
        $this->Table->addBehavior('Muffin/Crypt.Crypt');
    }

    /**
     * @expectedException \Cake\Core\Exception\Exception
     */
    public function testInitializeThrowsExceptionOnInvalidFieldType()
    {
        $this->Table->removeBehavior('Crypt');
        $fields = ['number' => 'foo'];
        $this->Table->addBehavior('Muffin/Crypt.Crypt', compact('fields'));
    }

    /**
     * @expectedException \Cake\Core\Exception\Exception
     */
    public function testInitializeThrowsExceptionOnInvalidStrategy()
    {
        $this->Table->removeBehavior('Crypt');
        $strategy = new \stdClass();
        $fields = 'number';
        $this->Table->addBehavior('Muffin/Crypt.Crypt', compact('strategy', 'fields'));
    }

    public function testEncrypt()
    {
        $strategy = $this->getMock(CryptBehavior::DEFAULT_STRATEGY, ['encrypt'], ['bar']);
        $this->Behavior->config(compact('strategy'));

        $strategy->expects($this->once())
            ->method('encrypt')
            ->with('foo')
            ->will($this->returnValue('foobar'));

        $result = $this->Table->encrypt('foo');
        $this->assertEquals('foobar', $result);
    }

    public function testDecrypt()
    {
        $strategy = $this->getMock(CryptBehavior::DEFAULT_STRATEGY, ['decrypt'], ['bar']);
        $this->Behavior->config(compact('strategy'));

        $strategy->expects($this->once())
            ->method('decrypt')
            ->with('foo')
            ->will($this->returnValue('foobar'));

        $result = $this->Table->decrypt('foo');
        $this->assertEquals('foobar', $result);
    }

    public function testFindDecrypted()
    {
        $result = $this->Table->find('decrypted')->toArray();
        $this->assertEquals('foo', $result[0]['number']);
        $this->assertEquals('bar', $result[1]['number']);
    }

    public function testBeforeSave()
    {
        $entity = $this->Table->newEntity(['number' => 'foobar']);
        $result = $this->Table->save($entity);
        $this->assertNotEquals('foobar', $result->number);
    }

    public function testBeforeFind()
    {
        $result = $this->Table->find()->toArray();
        $this->assertNotEquals('foo', $result[0]['number']);

        TableRegistry::clear();
        $table = TableRegistry::get('Muffin/Crypt.CreditCards', ['table' => 'crypt_credit_cards']);
        $table->addBehavior('Muffin/Crypt.Crypt', [
            'strategy' => new DefaultStrategy(getenv('CRYPT_TEST_SALT')),
            'fields' => 'number',
            'implementedEvents' => [
                'Model.beforeSave' => 'beforeSave',
                'Model.beforeFind' => 'beforeFind',
            ]
        ]);

        $result = $table->find()->toArray();
        $this->assertEquals('foo', $result[0]['number']);
    }
}
