<?php

namespace Process\Command;

class Multiplication extends SimpleCommand implements ICommand
{
    /**
     * This action implements the mathematical multiplication operation
     * @return int
     */
    public function execute(): int
    {
        return array_product($this->init_params);
    }
}
