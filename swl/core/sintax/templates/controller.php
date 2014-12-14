<?php

$this->theme = <<<EOF
class {$this->name}Controller extends {$this->extends}
{
    {$this->members}
    {$this->events}
    {$this->actions}
}

EOF
;
