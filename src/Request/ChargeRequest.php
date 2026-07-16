<?php

namespace Olek\WayForPay\Request;

use DateTimeInterface;
use InvalidArgumentException;
use Olek\WayForPay\Collection\ProductCollection;
use Olek\WayForPay\Contract\CardInterface;
use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Domain\ApplePay;
use Olek\WayForPay\Domain\Card;
use Olek\WayForPay\Domain\CardToken;
use Olek\WayForPay\Domain\Client;
use Olek\WayForPay\Domain\GooglePay;
use Olek\WayForPay\Enum\AuthType;
use Olek\WayForPay\Enum\TransactionSecure;
use Olek\WayForPay\Enum\TransactionType;
use Olek\WayForPay\Exception\WayForPayException;
use Olek\WayForPay\Response\ChargeResponse;

readonly class ChargeRequest extends ApiRequest
{
    public function __construct(
        Credential                $credential,
        private string            $orderReference,
        private float             $amount,
        private string            $currency,
        private ProductCollection $products,
        private DateTimeInterface $orderDate,
        private string            $merchantDomainName,
        private CardInterface     $card,
        private TransactionType   $merchantTransactionType = TransactionType::AUTO,
        private TransactionSecure $merchantTransactionSecureType = TransactionSecure::AUTO,
        private ?Client           $client = null,
        private ?string           $serviceUrl = null,
        private ?int              $holdTimeout = null,
        private AuthType          $merchantAuthType = AuthType::SIMPLE_SIGNATURE,
        private ?string           $socialUri = null,
    ) {
        parent::__construct($credential);

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

        if ($this->card instanceof Card) {
            $data["card"] = $this->card->getCard();
            $data["expMonth"] = sprintf("%02d", $this->card->getMonth());
            $data["expYear"] = (string)$this->card->getYear();
            $data["cardCvv"] = (string)$this->card->getCvv();
            $data["cardHolder"] = $this->card->getHolder();
        } elseif ($this->card instanceof CardToken) {
            $data["recToken"] = $this->card->getToken();
        } elseif ($this->card instanceof GooglePay) {
            $data["gpApiVersionMinor"] = $this->card->apiVersionMinor;
            $data["gpApiVersion"] = $this->card->apiVersion;
            $data["gpPMDescription"] = $this->card->paymentMethodData->description;
            $data["gpPMType"] = $this->card->paymentMethodData->type;
            $data["gpPMTCardNetwork"] = $this->card->paymentMethodData->info->cardNetwork;
            $data["gpPMTCardDetails"] = $this->card->paymentMethodData->info->cardDetails;
            $data["gpTokenizationType"] = $this->card->paymentMethodData->tokenizationData->type;
            $data["gpToken"] = $this->card->paymentMethodData->tokenizationData->token;
        } elseif ($this->card instanceof ApplePay) {
            $data["applePayString"] = $this->card->token;
        } else {
            throw new WayForPayException("Card or CardToken or GooglePay required");
        }

        return $data;
    }
    
    public function send(): ChargeResponse
    {
        $data = $this->sendRequest();
        return new ChargeResponse($data);
    }
}