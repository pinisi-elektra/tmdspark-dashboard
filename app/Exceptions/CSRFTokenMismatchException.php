<?php

namespace App\Exceptions;

use CRUDBooster;
use Exception;

class CSRFTokenMismatchException extends Exception
{
    public function render($request)
    {
        return redirect('admin/login')->with('toast_error', $this->message);
    }
}
