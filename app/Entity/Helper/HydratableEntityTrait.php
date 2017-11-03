<?php

namespace App\Entity\Helper;

use App\Helper\StringTransformer;

trait HydratableEntityTrait
{
    /**
     * @param array $data
     *
     * @return EntityInterface
     * @throws \Exception
     */
    public function hydrate($data)
    {
        if ($data instanceof \ArrayObject) {
            $data = $data->getArrayCopy();
        } else {
            if ($data instanceof \Iterator) {
                $data = iterator_to_array($data);
            }
        }

        if (!is_array($data)) {
            throw new \Exception(
                get_class($this) . ' entities must be hydrated using either an array, an ArrayObject or an Iterator'
            );
        }

        foreach ($data as $key => $value) {
            $methodName = 'set' . StringTransformer::toCamelCase($key);
            if (method_exists($this, $methodName)) {
                $this->$methodName($value);
            }
        }

        return $this;
    }
}