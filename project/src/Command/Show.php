<?php

namespace Process\Command;

class Show extends SimpleCommand implements ICommand
{
    /**
     * This action outputs all passed values to browser
     */
    public function execute(): void
    {
        echo '<br><hr><h3>Here is result:</h3>';
        echo '<br><ul>';
        foreach ($this->init_params as $name => $value) {
            printf('<li><b>%s:</b></li>', $value);
        }
        echo '</ul>';
        echo '<hr>';
    }
}
