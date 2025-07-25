<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitdeee9af68db3b2de6f06482fca67b5bb
{
    public static $prefixLengthsPsr4 = array (
        's' => 
        array (
            'setasign\\Fpdi\\' => 14,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'setasign\\Fpdi\\' => 
        array (
            0 => __DIR__ . '/..' . '/setasign/fpdi/src',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
        'FPDF' => __DIR__ . '/..' . '/setasign/fpdf/fpdf.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitdeee9af68db3b2de6f06482fca67b5bb::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitdeee9af68db3b2de6f06482fca67b5bb::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitdeee9af68db3b2de6f06482fca67b5bb::$classMap;

        }, null, ClassLoader::class);
    }
}
