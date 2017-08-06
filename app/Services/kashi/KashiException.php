<?php

namespace App\Services\Kashi;

use Exception;

/**
 * Al-Kashi Exception class defined by extending the built-in Exception class.
 *
 * @category  Math
 * @package   Kashi
 * @author    Khaled Al-Shamaa <khaled.alshamaa@gmail.com>
 * @copyright 2012-2014 Khaled Al-Shamaa
 *
 * @license   GPL <http://www.gnu.org/licenses/gpl.txt>
 * @version   5.0 released in Feb 2, 2014
 * @link      http://www.ar-php.org/stats/al-kashi
 */
class KashiException extends Exception
{
    /**
     * Make sure everything is assigned properly
     *
     * @param string $message Exception message
     * @param int    $code    User defined exception code
     */
    public function __construct($message, $code=0)
    {
        parent::__construct($message, $code);
    }
}
