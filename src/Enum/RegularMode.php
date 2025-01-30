<?php

namespace Olek\WayForPay\Enum;

enum RegularMode: string
{
    case CLIENT = "client";
    case NONE = "none";
    case ONCE = "once";
    case DAILY = "daily";
    case WEEKLY = "weekly";
    case QUARTERLY = "quarterly";
    case MONTHLY = "monthly";
    case HALFYEARLY = "halfyearly";
    case YEARLY = "yearly";
}
