<?php

namespace Process\Command;

class Sum extends SimpleCommand implements ICommand
{
    /**
     * This action implements the mathematical sum operation of all passed values
     * @return int
     */
    public function execute(): int
    {
        return array_sum($this->init_params);
    }
}
