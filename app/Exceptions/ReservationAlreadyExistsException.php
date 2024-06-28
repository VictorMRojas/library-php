<?php

namespace App\Exceptions;

use Exception;

class ReservationAlreadyExistsException extends Exception
{
    public function render()
    {
        return response()->json(['error' => 'La reserva ya existe.'], 409);
    }
}
