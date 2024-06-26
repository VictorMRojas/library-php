<?php

namespace App\Exceptions;

use Exception;

class ReservationAlreadyExistsException extends Exception
{
    public function render()
    {
        return response()->json(['error' => 'The reservation already exists'], 400);
    }
}
