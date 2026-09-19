<?php

namespace Olek\WayForPay\Request;

use DateTimeInterface;
use Olek\WayForPay\Collection\ProductCollection;
use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Domain\Avia;
use Olek\WayForPay\Domain\CardToken;
use Olek\WayForPay\Domain\Client;
use Olek\WayForPay\Domain\Delivery;
use Olek\WayForPay\Domain\PaymentSystems;
use Olek\WayForPay\Domain\Regular;
use Olek\WayForPay\Response\PurchaseResponse;

readonly class PurchaseRequest extends ApiRequest
{

    public function __construct(
        Credential                $credential,
        private string            $orderReference,
        private float             $amount,
        private string            $currency,
        private ProductCollection $products,
        private DateTimeInterface $orderDate,
        private string            $merchantDomainName,
        private ?string           $merchantTransactionType = null,
        private ?string           $merchantTransactionSecureType = null,
        private string            $endpointUrl = "https://secure.wayforpay.com/pay",
        private ?Client           $client = null,
        private ?Delivery         $delivery = null,
        private ?Avia             $avia = null,
        private ?Regular          $regular = null,
        private ?CardToken        $token = null,
        private ?PaymentSystems   $paymentSystems = null,
        private ?string           $serviceUrl = null,
        private ?string           $returnUrl = null,
        private ?int              $holdTimeout = null,
        private ?int              $orderTimeout = null,
        private ?int              $orderLifetime = null,
        private ?string           $socialUri = null,
        private ?string           $language = null,
        private ?string           $orderNo = null,
        private ?float            $alternativeAmount = null,
        private ?string           $alternativeCurrency = null,
    )
    {
        parent::__construct($credential);
    }

    protected function getRequestSignatureFieldsValues(): array
    {
        return array_merge(parent::getRequestSignatureFieldsValues(), [
            "merchantDomainName" => $this->merchantDomainName,
            "orderReference" => $this->orderReference,
            "orderDate" => $this->orderDate->getTimestamp(),
            "amount" => $this->amount,
            "currency" => $this->currency,
            "products" => $this->products,
        ]);
    }

    protected function getUrl(): string
    {
        return $this->endpointUrl . "?behavior=offline";
    }

    protected function getType(): string
    {
        return "PURCHASE";
    }

    protected function getTransactionData(): array
    {
        return array_merge(parent::getTransactionData(), [
            "merchantDomainName"            => $this->merchantDomainName,
            "merchantTransactionType"       => $this->merchantTransactionType,
            "merchantTransactionSecureType" => $this->merchantTransactionSecureType,
            "language"                      => $this->language,
            "returnUrl"                     => $this->returnUrl,
            "serviceUrl"                    => $this->serviceUrl,
            "orderReference"                => $this->orderReference,
            "orderDate"                     => $this->orderDate->getTimestamp(),
            "orderNo"                       => $this->orderNo,
            "amount"                        => $this->amount,
            "currency"                      => $this->currency,
            "alternativeAmount"             => $this->alternativeAmount,
            "alternativeCurrency"           => $this->alternativeCurrency,
            "holdTimeout"                   => $this->holdTimeout,
            "orderTimeout"                  => $this->orderTimeout,
            "orderLifetime"                 => $this->orderLifetime,
            "recToken"                      => $this->token?->getToken(),
            "productName"                   => $this->products->getNames(),
            "productPrice"                  => $this->products->getPrices(),
            "productCount"                  => $this->products->getCounts(),
            "socialUri"                     => $this->socialUri,
            "clientAccountId"               => $this->client?->getId(),
            "clientFirstName"               => $this->client?->getNameFirst(),
            "clientLastName"                => $this->client?->getNameLast(),
            "clientAddress"                 => $this->client?->getAddress(),
            "clientCity"                    => $this->client?->getCity(),
            "clientState"                   => $this->client?->getState(),
            "clientZipCode"                 => $this->client?->getZip(),
            "clientCountry"                 => $this->client?->getCountry(),
            "clientEmail"                   => $this->client?->getEmail(),
            "clientPhone"                   => $this->client?->getPhone(),
            "deliveryFirstName"             => $this->delivery?->getNameFirst(),
            "deliveryLastName"              => $this->delivery?->getNameLast(),
            "deliveryAddress"               => $this->delivery?->getAddress(),
            "deliveryCity"                  => $this->delivery?->getCity(),
            "deliveryState"                 => $this->delivery?->getState(),
            "deliveryZipCode"               => $this->delivery?->getZip(),
            "deliveryCountry"               => $this->delivery?->getCountry(),
            "deliveryEmail"                 => $this->delivery?->getEmail(),
            "deliveryPhone"                 => $this->delivery?->getPhone(),
            "aviaDepartureDate"             => $this->avia?->getDepartureDate()?->getTimestamp(),
            "aviaLocationNumber"            => $this->avia?->getLocationNumber(),
            "aviaLocationCodes"             => $this->avia?->getLocationCodes(),
            "aviaFirstName"                 => $this->avia?->getNameFirst(),
            "aviaLastName"                  => $this->avia?->getNameLast(),
            "aviaReservationCode"           => $this->avia?->getReservationCode(),
            "regularMode"                   => $this->regular?->getModesAsString(),
            "regularAmount"                 => $this->regular?->getAmount(),
            "dateNext"                      => $this->regular?->getDateNext()?->format("d.m.Y"),
            "dateEnd"                       => $this->regular?->getDateEnd()?->format("d.m.Y"),
            "regularCount"                  => $this->regular?->getCount(),
            "regularOn"                     => (int)$this->regular?->isOn(),
            "regularBehavior"               => $this->regular?->getBehaviorValue(),
            "paymentSystems"                => $this->paymentSystems?->getListAsString(),
            "defaultPaymentSystem"          => $this->paymentSystems?->getDefaultValue(),
        ]);
    }

    public function send(): PurchaseResponse
    {
        $data = $this->sendRequest();
        return new PurchaseResponse($data);
    }
}