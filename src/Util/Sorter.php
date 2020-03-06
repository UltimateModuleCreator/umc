<?php
namespace App\Util;

use App\Model\AbstractModel;

/**
 * @deprecated
 */
class Sorter
{
    /**
     * @param array $array
     * @param string $field
     * @param bool $valuesOnly
     * @return array
     */
    public function sort(array $array, $field = 'position', $valuesOnly = true) : array
    {
        uasort(
            $array,
            function (AbstractModel $modelA, AbstractModel $modelB) use ($field) {
                $sortOrderA = $modelA->getData($field);
                $sortOrderB = $modelB->getData($field);
                if ($sortOrderA === $sortOrderB) {
                    return 0;
                }
                // empty values are placed at the end
                if ($sortOrderB === "") {
                    return -1;
                }
                if ($sortOrderA === "") {
                    return 1;
                }
                return ((int)$sortOrderA < (int)$sortOrderB) ? -1 : 1;
            }
        );
        return ($valuesOnly) ? array_values($array) : $array;
    }
}
