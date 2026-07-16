<?php

namespace Olek\WayForPay\Request;

use DateTimeInterface;
use Olek\WayForPay\Collection\ProductCollection;
use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Domain\Client;
use Olek\WayForPay\Domain\PaymentSystems;
use Olek\WayForPay\Response\InvoiceResponse;

readonly class InvoiceRequest extends ApiRequest
{
    public function __construct(
        Credential        $credential,
        private string            $orderReference,
        private float             $amount,
        private string            $currency,
        private ProductCollection $products,
        private DateTimeInterface $orderDate,
        private string            $merchantDomainName,
        private ?Client           $client = null,
        private ?PaymentSystems   $paymentSystems = null,
        private ?string           $serviceUrl = null,
        private ?int              $holdTimeout = null,
        private ?int              $orderTimeout = null,
        private ?string           $language = null
    ) {
        parent::__construct($credential);
    }

    protected function getRequestSignatureFieldsValues(): array
    {
        return array_merge(parent::getRequestSignatureFieldsValues(), [
            "merchantDomainName" => $this->merchantDomainName,
            "orderReference"     => $this->orderReference,
            "orderDate"          => $this->orderDate->getTimestamp(),
            "amount"             => $this->amount,
            "currency"           => $this->currency,
            "products"           => $this->products,
        ]);
    }

    protected function getType(): string
    {
        return "CREATE_INVOICE";
    }

    protected function getTransactionData(): array
    {
        return array_merge(parent::getTransactionData(), [
            "merchantDomainName"   => $this->merchantDomainName,
            "language"             => $this->language,
            "serviceUrl"           => $this->serviceUrl,
            "orderReference"       => $this->orderReference,
            "orderDate"            => $this->orderDate->getTimestamp(),
            "amount"               => $this->amount,
            "currency"             => $this->currency,
            "holdTimeout"          => $this->holdTimeout,
            "orderTimeout"         => $this->orderTimeout,
            "productName"          => $this->products->getNames(),
            "productPrice"         => $this->products->getPrices(),
            "productCount"         => $this->products->getCounts(),
            "clientFirstName"      => $this->client->getNameFirst(),
            "clientLastName"       => $this->client->getNameLast(),
            "clientEmail"          => $this->client->getEmail(),
            "clientPhone"          => $this->client->getPhone(),
            "paymentSystems"       => $this->paymentSystems->getListAsString(),
            "defaultPaymentSystem" => $this->paymentSystems->getDefaultValue(),
        ]);
    }
    

    public function send(): InvoiceResponse
    {
        $data = $this->sendRequest();
        return new InvoiceResponse($data);
    }
}