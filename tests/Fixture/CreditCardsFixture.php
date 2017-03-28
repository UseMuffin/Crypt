<?php
namespace Muffin\Crypt\Test\Fixture;

use Cake\Core\Configure;
use Cake\TestSuite\Fixture\TestFixture;
use Cake\Utility\Security;

class CreditCardsFixture extends TestFixture
{
    public $table = 'crypt_credit_cards';

    public $fields = [
        'id' => ['type' => 'integer'],
        'number' => ['type' => 'string', 'length' => 2048],
        '_constraints' => [
            'primary' => ['type' => 'primary', 'columns' => ['id']],
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
