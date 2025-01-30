<?php

namespace Olek\WayForPay\Enum;

enum PaymentSystem: string
{
    case CARD = "card";
    case PRIVAT24 = "privat24";
    case LP_TERMINAL = "lpTerminal";
    case BTC = "btc";
    case BANK_CASH = "bankCash";
    case CREDIT = "credit";
    case PAY_PARTS = "payParts";
    case QR_CODE = "qrCode";
    case MASTER_PASS = "masterPass";
    case VISA_CHECKOUT = "visaCheckout";
    case GOOGLE_PAY = "googlePay";
    case APPLE_PAY = "applePay";
    case PAY_PARTS_MONO = "payPartsMono";
}
