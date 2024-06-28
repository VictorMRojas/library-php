<?php

namespace App\Exceptions;

use Exception;

class BookNotAvailableException extends Exception
{
    public function render()
    {
        return response()->json(['error' => 'El libro no está disponible.'], 409);
    }
}
