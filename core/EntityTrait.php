<?php

trait EntityTrait
{
    public function __call(string $name, array $arguments)
    {
        $prefix = substr($name, 0, 3);
        $property = lcfirst(substr($name, 3));

        if ($prefix === 'get') {
            $snakeCaseProperty = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
            if (property_exists($this, $snakeCaseProperty)) {
                return $this->$snakeCaseProperty;
            }
        }

        if ($prefix === 'set') {
            $snakeCaseProperty = strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $property));
            if (property_exists($this, $snakeCaseProperty)) {
                $this->$snakeCaseProperty = $arguments[0];
                return $this;
            }
        }

        throw new \Exception("Undefined method '$name', property '$property' does not exist with prefix '$prefix'.");
    }
}