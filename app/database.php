<?php

use Psr\Container\ContainerInterface;

return function (ContainerInterface $container) {
    $container->get('db');
};