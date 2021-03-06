<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitd64372594a98233948382a5750684f2f
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
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitd64372594a98233948382a5750684f2f::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitd64372594a98233948382a5750684f2f::$prefixDirsPsr4;

        }, null, ClassLoader::class);
    }
}
