<?php
/**
 * Test suite bootstrap.
 *
 * This function is used to find the location of CakePHP whether CakePHP
 * has been installed as a dependency of the plugin, or the plugin is itself
 * installed as a dependency of an application.
 */

$findRoot = function ($root) {
    do {
        $lastRoot = $root;
        $root = dirname($root);
        if (is_dir($root . '/vendor/cakephp/cakephp')) {
            return $root;
        }
    } while ($root !== $lastRoot);

    throw new Exception("Cannot find the root of the application, unable to run tests");
};
$root = $findRoot(__FILE__);
unset($findRoot);

chdir($root);
if (file_exists($root . '/config/bootstrap.php')) {
    require $root . '/config/bootstrap.php';
}

require $root . '/vendor/cakephp/cakephp/tests/bootstrap.php';

$path = dirname(__DIR__) . DS;
\Cake\Core\Plugin::load('Muffin/Crypt', compact('path'));

if (!getenv('CRYPT_TEST_SALT')) {
    putenv('CRYPT_TEST_SALT=Kh1YBxlntwjDO6gbVsyWr1aSOtIM06GDbRWnU2Nk');
}

if (!getenv('CRYPT_TEST_PRIVATE_KEY')) {
    putenv('CRYPT_TEST_PRIVATE_KEY=file://' . $path . 'tests' . DS . 'Fixture' . DS . 'mykey.pem');
}

if (!getenv('CRYPT_TEST_PUBLIC_KEY')) {
    putenv('CRYPT_TEST_PUBLIC_KEY=file://' . $path . 'tests' . DS . 'Fixture' . DS . 'pubkey.pem');
}
