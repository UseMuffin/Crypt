<?php
namespace Muffin\Crypt\Model\Behavior\Strategy;

interface StrategyInterface
{
    /**
     * @param string $plain String to encrypt.
     * @return string
     */
    public function encrypt($plain);

    /**
     * @param string $cipher Cipher to decrypt.
     * @return string
     */
    public function decrypt($cipher);
}
