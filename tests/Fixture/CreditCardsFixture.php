<?php
namespace Muffin\Crypt\Test\Fixture;

use Cake\Core\Configure;
use Cake\TestSuite\Fixture\TestFixture;
use Cake\Utility\Security;

class CreditCardsFixture extends TestFixture
{
    public $table = 'crypt_credit_cards';

    public $fields = [
        'id' => ['type' => 'integer', 'length' => 11, 'autoIncrement' => true],
        'number' => ['type' => 'string', 'length' => 2048],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id'], 'length' => []],
        ],
        '_options' => [
            'engine' => 'InnoDB',
            'collation' => 'utf8_general_ci'
        ],
    ];

    public $records = [
        ['number' => 'foo'],
        ['number' => 'bar'],
    ];

    public function init()
    {
        foreach ($this->records as &$record) {
            $record['number'] = Security::encrypt($record['number'], getenv('CRYPT_TEST_SALT'));
        }
        parent::init();
    }
}
