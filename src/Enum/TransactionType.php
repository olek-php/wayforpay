<?php

namespace Olek\WayForPay\Enum;

enum TransactionType: string
{
    case AUTO = "AUTO";
    case SALE = "SALE";
    case AUTH = "AUTH";
}
