<?php

namespace Olek\WayForPay\Enum;

enum TransactionStatus: string
{
    case CREATED                = "Created";
    case IN_PROCESSING          = "InProcessing";
    case WAIT_AUTH_COMPLETE     = "WaitingAuthComplete";
    case APPROVED               = "Approved";
    case PENDING                = "Pending";
    case EXPIRED                = "Expired";
    case REFUNDED               = "Refunded";
    case VOIDED                 = "Voided";
    case DECLINED               = "Declined";
    case REFUND_IN_PROCESSING   = "RefundInProcessing";
}