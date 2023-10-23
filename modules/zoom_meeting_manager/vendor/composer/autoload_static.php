<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit2b096856426f6a4c86485a1cd31a33f7
{
    public static $prefixLengthsPsr4 = array (
        'E' => 
        array (
            'EspressoDev\\Zoom\\' => 17,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'EspressoDev\\Zoom\\' => 
        array (
            0 => __DIR__ . '/..' . '/espresso-dev/zoom-php/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit2b096856426f6a4c86485a1cd31a33f7::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit2b096856426f6a4c86485a1cd31a33f7::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
