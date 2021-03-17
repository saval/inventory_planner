<?php

namespace Process\Storage;

use function is_numeric;

class SimpleIStorage implements IStorage
{
    protected $data;
    
    /**
     * SimpleIStorage constructor.
     * @param array $data
     */
    public function __construct(array $data)
    {
        $this->data = $data;
    }
    
    /**
     * @param string $key
     * @param string $value
     */
    public function addData(string $key, string $value): void
    {
        $this->data[$key] = $value;
    }
    
    /**
     * @param string $key
     * @return mixed|null
     */
    public function getValue(string $key)
    {
        return $this->data[$key] ?? null;
    }
    
    /**
     * @param array $keys_ar
     * @return array
     */
    public function getManyValues(array $keys_ar): array
    {
        if (!$keys_ar) {
            return [];
        }
        $values_ar = [];
        foreach ($keys_ar as $id => $key_name) {
            if (is_numeric($id)) {
                $values_ar[$key_name] = $this->getValue($key_name);

            } else {
                $values_ar[$id] = $this->getValue($key_name) ?? $key_name;
            }
        }
        return $values_ar;
    }
}
