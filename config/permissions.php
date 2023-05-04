<?php

declare(strict_types=1);

return [
    [
        'name' => 'Email Log',
        'flag' => 'email-logs.index',
    ],
    [
        'name' => 'Show',
        'flag' => 'email-logs.show',
        'parent_flag' => 'email-logs.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'email-logs.destroy',
        'parent_flag' => 'email-logs.index',
    ],
];
