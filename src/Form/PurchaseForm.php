<?php

namespace Olek\WayForPay\Form;

use DateTimeInterface;
use Olek\WayForPay\Collection\ProductCollection;
use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Domain\Avia;
use Olek\WayForPay\Domain\CardToken;
use Olek\WayForPay\Domain\Client;
use Olek\WayForPay\Domain\Delivery;
use Olek\WayForPay\Domain\PaymentSystems;
use Olek\WayForPay\Domain\Regular;
use Olek\WayForPay\Helper\SignatureHelper;

final readonly class PurchaseForm
{


    public function __construct(
        private Credential        $credential,
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
    ) {
    }

    public function getData()
    {
        $fieldForSignature = [
            "merchantAccount" => $this->credential->getAccount(),
            "merchantDomainName" => $this->merchantDomainName,
            "orderReference" => $this->orderReference,
            "orderDate" => $this->orderDate->getTimestamp(),
            "amount" => $this->amount,
            "currency" => $this->currency,
            "products" => $this->products
        ];
        $signature = SignatureHelper::calculateSignature($fieldForSignature, $this->credential->getSecret());
        $data = array(
            "merchantAccount"               => $this->credential->getAccount(),
            "merchantDomainName"            => $this->merchantDomainName,
            "merchantTransactionType"       => $this->merchantTransactionType,
            "merchantTransactionSecureType" => $this->merchantTransactionSecureType,
            "merchantSignature"             => $signature,
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
        );

        return array_filter($data);
    }


    public function getAsString($submitText = "Pay", $buttonClass = "btn btn-primary")
    {

        $form = sprintf(
            '<form method="POST" action="%s" accept-charset="utf-8">',
            $this->endpointUrl
        );

        foreach ($this->getData() as $key => $value) {
            if (is_array($value)) {
                foreach ($value as $field) {
                    $form .= sprintf(
                        '<input type="hidden" name="%s" value="%s" />',
                        $key . "[]",
                        htmlspecialchars($field)
                    );
                }
            } else {
                $form .= sprintf(
                    '<input type="hidden" name="%s" value="%s" />',
                    $key,
                    htmlspecialchars($value)
                );
            }
        }

        $form .= sprintf(
            '<input type="submit" value="%s" class="%s">',
            $submitText,
            $buttonClass
        );

        $form .= '</form>';

        return $form;
    }

    public function getWidget($callbackJsFunction = null, $buttonText = "Pay", $buttonClass = "btn btn-primary"): string
    {
        return $this->getWidgetExternalScript() .
            $this->getWidgetInitScript($callbackJsFunction) .
            $this->getWidgetButton($buttonText, $buttonClass);
    }

    private function getWidgetExternalScript(): string
    {
        return sprintf(
            '<script defer async id="widget-wfp-script" language="javascript" type="text/javascript" onload="wfpInit()" src="%s"></script>',
            "https://secure.wayforpay.com/server/pay-widget.js"
        );
    }

    private function getWidgetInitScript($callbackJsFunction = null): string
    {
        return sprintf(
            '<script type="text/javascript">
                var wayforpay = null;
                var wfpPay = function () {
                    wayforpay.run(%s);
                }
                var wfpInit = function() {      
                    wayforpay = new Wayforpay();
                    
                    window.addEventListener("message", %s);
                    
                    function receiveMessage(event)
                    {
                        if(event.data === "WfpWidgetEventClose"       // при закрытии виджета пользователем
                           || event.data === "WfpWidgetEventApproved" // при успешном завершении операции
                           || event.data === "WfpWidgetEventDeclined" // при неуспешном завершении
                           || event.data === "WfpWidgetEventPending"  // транзакция на обработке
                        ) {
                            console.log(event.data);
                        }
                    }
                }
            </script>',
            json_encode($this->getData()),
            $callbackJsFunction ?? "receiveMessage"
        );
    }

    private function getWidgetButton($buttonText = "Pay", $buttonClass = "btn btn-primary"): string
    {
        return sprintf(
            '<button class="%s" type="button" onclick="wfpPay();">%s</button>',
            $buttonClass,
            $buttonText
        );
    }
}
