<?php

namespace App\Enums;

enum TaskStatus : string
{
    case Completed = 'completed';
    
    case Uncompleted = 'uncompleted';
}
