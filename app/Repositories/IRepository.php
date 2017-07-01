<?php

namespace App\Repositories;

/**
 * Interface IRepository
 *
 * @author  Rob Attfield <emailme@robertattfield.com> <http://www.robertattfield.com>
 * @package App\Repositories
 */
interface IRepository
{
    /**
     * Method to sanitize data for classes implementing the IRepository interface.
     *
     * @param array $input
     *
     * @return mixed
     */
    public static function cleanInput(array $input);
}
