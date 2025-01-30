<?php

namespace Olek\WayForPay\Request;

use Olek\WayForPay\Contract\CredentialInterface;
use Olek\WayForPay\Contract\RequestInterface;
use Olek\WayForPay\Domain\Reason;
use Olek\WayForPay\Exception\ApiException;
use Olek\WayForPay\Exception\SignatureException;
use Olek\WayForPay\Helper\SignatureHelper;
use Symfony\Component\HttpClient\HttpClient;
use Throwable;

abstract class ApiRequest implements RequestInterface
{
    public const API_VERSION = 1;


    public function __construct(private readonly CredentialInterface $credential)
    {
    }

    abstract protected function getType(): string;

    protected function getTransactionData(): array
    {
        return [
            "transactionType" => $this->getType(),
            "merchantAccount" => $this->credential->getAccount(),
            "merchantSignature" => SignatureHelper::calculateSignature(
                $this->getRequestSignatureFieldsValues(),
                $this->credential->getSecret()
            ),
            "apiVersion" => self::API_VERSION,
        ];
    }

    protected function getRequestSignatureFieldsValues(): array
    {
        return [
            "merchantAccount" => $this->credential->getAccount(),
        ];
    }

    protected function getResponseSignatureFieldsRequired(): array
    {
        return [];
    }


    protected function sendRequest(): array
    {
        $options = [
            "json" => array_filter($this->getTransactionData())
        ];

        try {
            $http = HttpClient::create();
            $response = $http->request("POST", "https://api.wayforpay.com/api", $options);
            $data = $response->toArray(false);
        } catch (Throwable $e) {
            throw new ApiException(new Reason(-1, $e->getMessage()));
        }
        $this->checkSignature($data);
        return $data;
    }

    private function checkSignature(array $data): void
    {
        if ($signatureRequired = array_flip($this->getResponseSignatureFieldsRequired())) {
            $expected = SignatureHelper::calculateSignature(
                array_intersect_key(
                    array_replace($signatureRequired, $data),
                    $signatureRequired
                ),
                $this->credential->getSecret()
            );

            if (!isset($data["merchantSignature"]) || $expected !== $data["merchantSignature"]) {
                throw new SignatureException($expected, $data["merchantSignature"] ?? "");
            }
        }
    }
}