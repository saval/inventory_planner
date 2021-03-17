<?php

namespace Process\Storage;

interface IStorage
{
    public function __construct(array $init_data);
    public function addData(string $key, string $value): void;
    public function getValue(string $key);
    public function getManyValues(array $keys_ar);
}
