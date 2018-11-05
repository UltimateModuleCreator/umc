<?php
namespace App;

class Version
{
    const VERSION = '3.0.0';
    const BUILD = 'alpha1';

    /**
     * @return string
     */
    public static function getVersion()
    {
        return self::VERSION . ((self::BUILD) ? '-' . self::BUILD : '');
    }
}
