<?php

namespace LiveControls\Utils\Enums;

enum SocialSecurityNumberTypes: int
{
    case INVALID = 0;
    case CPF = 1;
    case CNPJ = 2;
}
