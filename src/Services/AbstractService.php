<?php
namespace BookManager\Services;

use BookManager\Contracts\ServicesInterface;
use Psr\Container\ContainerInterface;
abstract class AbstractService implements ServicesInterface {
    protected $container;
  
    public function boot()
    {
        return $this;
    }
    public function getContainer() : ContainerInterface
    {
        return $this->container;
    }
    public function setContainer(ContainerInterface $container)
    {
        $this->container = $container;
        return $this;
    }
}