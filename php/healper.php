<?php

function dd($var) {
    ?>
        <pre>
            <code>
                <?= var_dump($var); ?>
            </code>
        </pre>
    <?=
    die();
} ?>

