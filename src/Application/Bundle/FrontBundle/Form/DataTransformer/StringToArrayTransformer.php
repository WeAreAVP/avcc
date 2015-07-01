<?php
/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@avpreserve.com>
 * @author   Rimsha Khalid <rimsha@avpreserve.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.avpreserve.com
 */
namespace Application\Bundle\FrontBundle\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;

class StringToArrayTransformer implements DataTransformerInterface
{
    /**
     * Transforms an array to a string.
     * POSSIBLE LOSS OF DATA
     *
     * @return string
     */
    public function transform($array)
    {
        return $array[0];
    }

    /**
     * Transforms a string to an array.
     *
     * @param string $string
     *
     * @return array
     */
    public function reverseTransform($string)
    {
        return array($string);
    }
}
