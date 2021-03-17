<?php

namespace Process\Command;

use Process\Storage\IStorage as IStorage;

interface ICommand
{
    /**
     * @param IStorage $storage
     * @param mixed ...$params
     */
    public function __construct(IStorage $storage, ...$params);
    
    /**
     * Main routine, all action logic should be defined here
     * @return mixed
     */
    public function execute();
}
