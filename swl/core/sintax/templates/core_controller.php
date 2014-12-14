<?php

$this->theme = <<<EOF
abstract class {$this->name}Controller extends {$this->extends}
{
    {$this->members}
    {$this->events}
    {$this->methods}
}

EOF
;
