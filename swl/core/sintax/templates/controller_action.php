<?php

$this->theme = <<<EOF

    public function {$this->action}Action ({$this->arguments})
    {
        {$this->methodStatement}
    }

EOF
;
