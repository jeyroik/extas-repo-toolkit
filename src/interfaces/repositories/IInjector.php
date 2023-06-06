<?php
namespace extas\interfaces\repositories;

/**
 * Will be implemented in 0.2.0
 * 
 * @author jeyroik <jeyroik@gmail.com>
 */
interface IInjector
{
    /**
     * Get file $path content.
     * Will NOT run any code.
     *
     * @param string $path
     * @return string
     */
    public static function get(string $path): string;

    /**
     * Require file by $path.
     * Will run the code, if file contains php.
     *
     * @param string $path
     * @return string
     */
    public static function req(string $path): string;
}
