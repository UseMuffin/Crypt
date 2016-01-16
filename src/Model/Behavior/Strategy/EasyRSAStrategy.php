<?php
namespace Muffin\Crypt\Model\Behavior\Strategy;

use ParagonIE\EasyRSA\EasyRSA;

class EasyRSAStrategy extends AsymmetricStrategy
{

    public function encrypt($plain)
    {
        EasyRSA::encrypt($plain, $this->_publicKey);
    }

    public function decrypt($cipher)
    {
        EasyRSA::decrypt($cipher, $this->_privateKey);
    }
}
