<?php
namespace BookManager\Contracts;

interface ModelInterface
{
    public function save();
    public function delete();
    public function find($id);
    public function all();

}