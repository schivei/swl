<?php

$this->theme = <<<EOF

    protected function {$this->event} ({$this->arguments})
    {
        parent::{$this->event}({$this->arguments});
        {$this->methodStatement}
    }

EOF
;
