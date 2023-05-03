<?php

declare(strict_types=1);

return [
    [
        'name' => 'Email Log',
        'flag' => 'email-logs.index',
    ],
    [
        'name' => 'Delete',
        'flag' => 'email-logs.delete',
        'parent_flag' => 'email-logs.index',
    ],
];
