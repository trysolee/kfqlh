<?php

$a = [
    'ab' => function () {
        return 'try';
    },
];

echo $a['ab']();
