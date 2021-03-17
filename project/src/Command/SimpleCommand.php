<?php

namespace Process\Command;

use Process\Storage\IStorage as IStorage;

class SimpleCommand implements ICommand
{
    protected $init_params = [];
    
    /**
     * In the constructor actions usually fetch all needed for action parameters from the storage.
     * @param IStorage $storage
     * @param mixed ...$params
     */
    public function __construct(IStorage $storage, ...$params)
    {
        $this->init_params = $storage->getManyValues($params[0]);
    }
    
    public function execute()
    {
        return true;
    }
}
