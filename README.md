# Crypt

[![Build Status](https://img.shields.io/travis/UseMuffin/Crypt/master.svg?style=flat-square)](https://travis-ci.org/UseMuffin/Crypt)
[![Coverage](https://img.shields.io/codecov/c/github/UseMuffin/Crypt/master.svg?style=flat-square)](https://codecov.io/github/UseMuffin/Crypt)
[![Total Downloads](https://img.shields.io/packagist/dt/muffin/crypt.svg?style=flat-square)](https://packagist.org/packages/muffin/crypt)
[![License](https://img.shields.io/badge/license-MIT-blue.svg?style=flat-square)](LICENSE)

CakePHP 3 behavior to allow (a)symmetric encryption/decryption of data by the ORM.

Special thanks to security experts [@ircmaxell] & [@voodooKobra] for [reviewing the work][1].

[@ircmaxell]:https://twitter.com/ircmaxell
[@voodooKobra]:https://twitter.com/voodooKobra
[1]:https://twitter.com/jadb/status/688422888152657920

**USE AT YOUR OWN RISK.

## Install

Using [Composer][composer]:

```
composer require muffin/crypt:dev-master
```

You then need to load the plugin. You can use the shell command:

```
bin/cake plugin load Muffin/Crypt
```

or by manually adding statement shown below to `bootstrap.php`:

```php
Plugin::load('Muffin/Crypt');
```

## Usage

By default, the behavior will use the `Cake\Utility\Security` and not decrypt every find operation. Both configuration
could be overridden when setting up the behavior:

```
$this->addBehavior('Muffin/Crypt.Crypt', [
    'fields' => ['cc_number', 'cc_cvv'],
    'strategy' => '\Muffin\Crypt\Model\Behavior\Strategy\AsymmetricStrategy',
    'implementedEvents' => [
        'Model.beforeSave' => 'beforeSave',
        'Model.beforeFind' => 'beforeFind',
    ]
]);
```

If the fields you are encrypting are of a specific type (i.e. `POINT`), and if specified when configuring the behavior,
the behavior will take care of transforming the data back and forth. If using a custom type, make sure it is added to
your table.

```
$this->addBehavior('Muffin/Crypt.Crypt', [
    'fields' => ['location' => 'point'],
]);
```

## Patches & Features

* Fork
* Mod, fix
* Test - this is important, so it's not unintentionally broken
* Commit - do not mess with license, todo, version, etc. (if you do change any, bump them into commits of
their own that I can ignore when I pull)
* Pull request - bonus point for topic branches

To ensure your PRs are considered for upstream, you MUST follow the [CakePHP coding standards][standards].

## Bugs & Feedback

http://github.com/usemuffin/crypt/issues

## License

Copyright (c) 2017, [Use Muffin][muffin] and licensed under [The MIT License][mit].

[cakephp]:http://cakephp.org
[composer]:http://getcomposer.org
[mit]:http://www.opensource.org/licenses/mit-license.php
[muffin]:http://usemuffin.com
[standards]:http://book.cakephp.org/3.0/en/contributing/cakephp-coding-conventions.html
