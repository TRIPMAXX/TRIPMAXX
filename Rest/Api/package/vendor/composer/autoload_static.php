<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd247e95a8ab6b56fde23eb0406451ea0
{
    public static $prefixLengthsPsr4 = array (
        'F' => 
        array (
            'Firebase\\JWT\\' => 13,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Firebase\\JWT\\' => 
        array (
            0 => __DIR__ . '/..' . '/firebase/php-jwt/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd247e95a8ab6b56fde23eb0406451ea0::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd247e95a8ab6b56fde23eb0406451ea0::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
