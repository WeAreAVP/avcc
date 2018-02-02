<?php
/**
 * AVCC
 * 
 * @category AVCC
 * @package  Application
 * @author   Nouman Tayyab <nouman@weareavp.com>
 * @author   Rimsha Khalid <rimsha@weareavp.com>
 * @license  AGPLv3 http://www.gnu.org/licenses/agpl-3.0.txt
 * @copyright Audio Visual Preservation Solutions, Inc
 * @link     http://avcc.weareavp.com
 */
namespace Application\Bundle\FrontBundle\Helper;

use Symfony\Component\Templating\Helper\Helper;

class ViewHelper extends Helper
{

    /**
     * @inheritdoc
     */
    public function getName()
    {
        return "myViewHelper";
    }

    public function sortByOneKey(array $array, $key, $asc = TRUE)
    {
        $result = array();

        $values = array();
        foreach ($array as $id => $value) {
            if (is_object($value)) {
                $values[$id] = isset($value->$key) ? $value->$key : '';
            } else {
                $values[$id] = isset($value[$key]) ? $value[$key] : '';
            }
        }

        if ($asc) {
            asort($values);
        } else {
            arsort($values);
        }

        foreach ($values as $index => $value) {
            $check_value = '';
            if (is_object($array[$index])) {
                $check_value = $array[$index]->$key;
            } else
                $check_value = $array[$index][$key];
            if (trim($check_value) != '') {

                $result[] = $array[$index];
            }
        }

        return $result;
    }

}
