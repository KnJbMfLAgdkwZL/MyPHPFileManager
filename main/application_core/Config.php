<?php

class Config
{
    public static function get()
    {
        $s = DIRECTORY_SEPARATOR;
        return parse_ini_file("{$s}config{$s}main.ini", true);
    }
}