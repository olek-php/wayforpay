<?php

namespace Olek\WayForPay\Request;

use DateTimeInterface;
use InvalidArgumentException;
use Olek\WayForPay\Collection\ProductCollection;
use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Domain\Card;
use Olek\WayForPay\Domain\CardToken;
use Olek\WayForPay\Domain\Client;
use Olek\WayForPay\Enum\AuthType;
use Olek\WayForPay\Enum\TransactionSecure;
use Olek\WayForPay\Enum\TransactionType;
use Olek\WayForPay\Exception\WayForPayException;
use Olek\WayForPay\Response\ChargeResponse;

class ChargeRequest extends ApiRequest
{
    public function __construct(
        Credential                         $credential,
        private readonly string            $orderReference,
        private readonly float             $amount,
        private readonly string            $currency,
        private readonly ProductCollection $products,
        private readonly DateTimeInterface $orderDate,
        private readonly string            $merchantDomainName,
        private readonly ?Card             $card = null,
        private readonly ?CardToken        $recToken = null,
        private readonly TransactionType   $merchantTransactionType = TransactionType::AUTO,
        private readonly TransactionSecure $merchantTransactionSecureType = TransactionSecure::AUTO,
        private readonly ?Client            $client = null,
        private readonly ?string           $serviceUrl = null,
        private readonly ?int              $holdTimeout = null,
        private readonly AuthType          $merchantAuthType = AuthType::SIMPLE_SIGNATURE,
        private readonly ?string           $socialUri = null,
    ) {
        parent::__construct($credential);

        if ($this->card === null && $this->recToken === null) {
            throw new InvalidArgumentException("Card or CardToken required");
        }

        if (strlen($currency) !== 3) {
            throw new InvalidArgumentException("Currency must contain 3 chars");
        }
    }

    protected function getRequestSignatureFieldsValues(): array
    {
        return array_merge(parent::getRequestSignatureFieldsValues(), [
            "merchantDomainName" => $this->merchantDomainName,
            "orderReference" => $this->orderReference,
            "orderDate" => $this->orderDate->getTimestamp(),
            "amount" => $this->amount,
            "currency" => $this->currency,
            "products" => $this->products
        ]);
    }

    protected function getResponseSignatureFieldsRequired(): array
    {
        return [
            "merchantAccount",
            "orderReference",
            "amount",
            "currency",
            "authCode",
            "cardPan",
            "transactionStatus",
            "reasonCode",
        ];
    }

    protected function getType(): string
    {
        return "CHARGE";
    }

    protected function getTransactionData(): array
    {
        $data = array_merge(parent::getTransactionData(), [
            "merchantAuthType"              => $this->merchantAuthType->value,
            "merchantDomainName"            => $this->merchantDomainName,
            "merchantTransactionType"       => $this->merchantTransactionType->value,
            "merchantTransactionSecureType" => $this->merchantTransactionSecureType->value,
            "serviceUrl"                    => $this->serviceUrl,
            "orderReference"                => $this->orderReference,
            "orderDate"                     => $this->orderDate->getTimestamp(),
            "amount"                        => $this->amount,
            "currency"                      => $this->currency,
            "holdTimeout"                   => $this->holdTimeout,
            "socialUri"                     => $this->socialUri,
            "clientAccountId"               => $this->client?->getId(),
            "clientFirstName"               => $this->client?->getNameFirst(),
            "clientLastName"                => $this->client?->getNameLast(),
            "clientEmail"                   => $this->client?->getEmail(),
            "clientPhone"                   => $this->client?->getPhone(),
            "clientCountry"                 => $this->client?->getCountry(),
            "clientIpAddress"               => $this->client?->getIp(),
            "clientAddress"                 => $this->client?->getAddress(),
            "clientCity"                    => $this->client?->getCity(),
            "clientState"                   => $this->client?->getState(),
            "productName"                   => $this->products->getNames(),
            "productPrice"                  => $this->products->getPrices(),
            "productCount"                  => $this->products->getCounts(),
        ]);

        if ($this->card) {
            $data = array_merge($data, [
                "card" => $this->card->getCard(),
                "expMonth" => sprintf("%02d", $this->card->getMonth()),
                "expYear" => (string)$this->card->getYear(),
                "cardCvv" => (string)$this->card->getCvv(),
                "cardHolder" => $this->card->getHolder(),
            ]);
        } elseif ($this->recToken) {
            $data = array_merge($data, [
                "recToken" => $this->recToken->getToken(),
            ]);
        } else {
            throw new WayForPayException("Card or token required");
        }

        return $data;
    }
    
    public function send(): ChargeResponse
    {
        $data = $this->sendRequest();
        return new ChargeResponse($data);
    }
}