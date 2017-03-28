<?php
namespace Muffin\Crypt;

if (!function_exists('\Muffin\Crypt\public_encrypt')) {
    /**
     * @param string $plain String to encrypt.
     * @param string $certificate Public key certificate, one of:
     *   - an X.509 certificate resource
     *   - a PEM formatted public key
     *   - a string having the format `file://path/to/file.pem`. The named file
     *   must contain a PEM encoded certificate/public key (it may contain both).
     * @return string
     */
    function public_encrypt($plain, $certificate)
    {
        openssl_public_encrypt($plain, $cipher, openssl_get_publickey($certificate), OPENSSL_PKCS1_OAEP_PADDING);

        return base64_encode($cipher);
    }
}

if (!function_exists('\Muffin\Crypt\private_decrypt')) {
    /**
     * @param string $cipher Cipher to decrypt.
     * @param string $certificate Private key certificate, one of:
     *   - a string having the format file://path/to/file.pem. The named file
     *   must contain a PEM encoded certificate.
     *   - a PEM encoded certificate
     * @param string $passphrase Private key's associated passphrase, if any.
     * @return string
     */
    function private_decrypt($cipher, $certificate, $passphrase = null)
    {
        $resource = openssl_get_privatekey($certificate, $passphrase);
        openssl_private_decrypt(base64_decode($cipher), $plain, $resource, OPENSSL_PKCS1_OAEP_PADDING);

        return $plain;
    }
}
