<?php

namespace Olek\WayForPay\Enum;

enum RegularBehavior: string
{
    case NONE = "none";
    case DEFAULT = "default";
    case PRESET = "preset";
}
