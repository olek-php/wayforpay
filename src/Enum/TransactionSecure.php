<?php

namespace Olek\WayForPay\Enum;

enum TransactionSecure: string
{
    case AUTO = "AUTO";
    case WITH_3DS = "3DS";
    case NON_3DS = "NON3DS";
}
