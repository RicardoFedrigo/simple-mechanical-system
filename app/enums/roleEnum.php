<?php

namespace App\Enums;

enum RoleEnum: string
{
    case Admin = 'Admin';
    case Mechanic = 'Mechanic';
    case Attendant = 'Attendant';
}
