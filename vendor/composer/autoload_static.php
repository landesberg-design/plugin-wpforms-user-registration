<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit19d5b9c00bded9342c8a35415d165eea
{
    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPFormsUserRegistration\\' => 24,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPFormsUserRegistration\\' => 
        array (
            0 => __DIR__ . '/../..' . '/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit19d5b9c00bded9342c8a35415d165eea::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit19d5b9c00bded9342c8a35415d165eea::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit19d5b9c00bded9342c8a35415d165eea::$classMap;

        }, null, ClassLoader::class);
    }
}
