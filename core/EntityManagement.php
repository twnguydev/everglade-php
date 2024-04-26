<?php

namespace Core\Entity;

class EntityManagement
{
    public function __construct(array $data = [])
    {
        $this->fromArray($data);
    }

    public function toArray(): array
    {
        return (array) $this;
    }

    public function fromArray(array $data): void
    {
        foreach ($data as $property => $value) {
            if (property_exists($this, $property)) {
                $this->$property = $value;
            }
        }
    }

    public function __toString(): string
    {
        return json_encode($this->toArray());
    }
}