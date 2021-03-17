<?php

namespace Process\Command;

class Power extends SimpleCommand implements ICommand
{
    /**
     * This action implements the mathematical operation of raising a number to an arbitrary power
     * @return int
     * @throws \Exception
     */
    public function execute(): int
    {
        if (empty($this->init_params['base'])) {
            throw new \Exception('Power action. Missing [base] parameter');
        }
        if (empty($this->init_params['exp'])) {
            throw new \Exception('Power action. Missing [exp] parameter');
        }
        return pow($this->init_params['base'], $this->init_params['exp']);
    }
}
