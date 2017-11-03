<?php

namespace App\Entity\Helper;

use App\Helper\StringTransformer;

trait ObjectTransformerTrait
{
    /**
     * @param int $propertyStyle
     * @return array
     */
    public function toArray(int $propertyStyle = PropertyStringStylesInterface::DEFAULT): array
    {
        $data = [];
        $methods = get_class_methods(get_class($this));

        foreach ($methods as $method) {
            if (substr($method, 0, 3) == 'get') {
                $property = lcfirst(substr($method, 3));

                if (PropertyStringStylesInterface::SNAKE_CASE  === $propertyStyle) {
                    $property = StringTransformer::toSnakeCase($property);
                }

                $value = $this->$method();
                if ($value instanceof \DateTime) {
                    $value = $value->format('c');
                }

                $data[$property] = $value;
            }
        }

        return $data;
    }
}
