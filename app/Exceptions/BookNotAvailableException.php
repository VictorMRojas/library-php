<?php

namespace App\Exceptions;

use Exception;

class BookNotAvailableException extends Exception
{
    public function render()
    {
        return response()->json(['error' => 'The book is not available'], 400);
    }
}
