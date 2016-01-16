<?php
namespace Muffin\Crypt\Model\Behavior\Strategy;

use Cake\Core\Exception\Exception;
use Muffin\Crypt;

/**
 * Class AsymmetricStrategy
 *
 * @package Muffin\Crypt\Model\Behavior\Strategy
 */
class AsymmetricStrategy implements StrategyInterface
{
    /**
     * Public key.
     *
     * @var resource
     */
    protected $_publicKey;

    /**
     * Private key.
     *
     * @var resource
     */
    protected $_privateKey;

    /**
     * AsymmetricStrategy constructor.
     *
     * @param string $public Valid public certificate, can be:
     *   - an X.509 certificate resource
     *   - a PEM formatted public key
     *   - a string having the format `file://path/to/file.pem`. The named file
     *   must contain a PEM encoded certificate/public key (it may contain both).
     * @param string $public Optional. A valid private certificate, can be:
     *   - a string having the format file://path/to/file.pem. The named file
     *   must contain a PEM encoded certificate.
     *   - a PEM encoded certificate
     * @param string $passphrase Optional pass phrase.
     * @throws \Cake\Core\Exception\Exception
     */
    public function __construct($public, $private = null, $passphrase = null)
    {
        if (!$this->_publicKey = openssl_get_publickey($public)) {
            throw new Exception('Invalid public certificate: ' . $public);
        }

        if ($private !== null && !$this->_privateKey = openssl_get_privatekey($private, $passphrase)) {
            throw new Exception('Invalid private certificate: ' . $private);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function encrypt($plain)
    {
        return Crypt\public_encrypt($plain, $this->_publicKey);
    }

    /**
     * {@inheritdoc}
     */
    public function decrypt($cipher)
    {
        if (!$this->_privateKey) {
            return $cipher;
        }

        try {
            return Crypt\private_decrypt($cipher, $this->_privateKey);
        } catch (\Exception $e) {
            return $cipher;
        }
    }
}
