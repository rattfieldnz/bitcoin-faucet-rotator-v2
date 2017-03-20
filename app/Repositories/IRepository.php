<?php

namespace App\Repositories;


interface IRepository
{
    static function cleanInput(array $input);
}