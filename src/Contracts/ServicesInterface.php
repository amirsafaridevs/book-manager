<?php
namespace BookManager\Contracts;

use Psr\Container\ContainerInterface;

interface ServicesInterface
{
    public function boot();
    public function getContainer(): ContainerInterface;
    public function setContainer(ContainerInterface $container);
}