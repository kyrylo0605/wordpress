<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit801dc1ede333bc9d2b16c3370bc2210b
{
    public static $files = array (
        '3c811c5eee2f69449ba771bff79ea54a' => __DIR__ . '/..' . '/codeinwp/ti-about-page/load.php',
        'c8e9888657e6defd3de05726d7b39ae1' => __DIR__ . '/..' . '/codeinwp/ti-onboarding/load.php',
        'c730ac5ba4946398dd12db7e8d42d1c8' => __DIR__ . '/..' . '/codeinwp/themeisle-sdk/load.php',
        '4c3bcd61dc8e4dc113d6d770892056fe' => __DIR__ . '/..' . '/codeinwp/ti-about-page/load.php',
        '11c10943e97268bbf2aa201d18da2c4f' => __DIR__ . '/..' . '/codeinwp/ti-onboarding/load.php',
    );

    public static $prefixLengthsPsr4 = array (
        'H' => 
        array (
            'HFG\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'HFG\\' => 
        array (
            0 => __DIR__ . '/../..' . '/header-footer-grid',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit801dc1ede333bc9d2b16c3370bc2210b::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit801dc1ede333bc9d2b16c3370bc2210b::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
