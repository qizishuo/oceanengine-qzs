<?php

namespace OceanengineQzs\Exceptions;
use Exception as ExceptionBase;
class Exception extends ExceptionBase
{
    public function __construct($message, $code = 0)
    {
        parent::__construct($message, $code);
    }}
