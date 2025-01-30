<?php

namespace Olek\WayForPay\Handler;

use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Domain\TransactionService;
use Olek\WayForPay\Exception\SignatureException;
use Olek\WayForPay\Helper\SignatureHelper;
use Olek\WayForPay\Response\ServiceResponse;

readonly class ServiceUrlHandler
{
    public const STATUS_ACCEPT = "accept";

    public function __construct(protected Credential $credential)
    {
    }

    protected function getFieldForSignature(ServiceResponse $response): array
    {
        $transaction = $response->getTransaction();
        return [
            $this->credential->getAccount(),
            $transaction->getOrderReference(),
            $transaction->getAmount(),
            $transaction->getCurrency(),
            $transaction->getAuthCode(),
            $transaction->getCardPan(),
            $transaction->getStatus()->value,
            $response->getReason()->getCode()
        ];
    }

    public function parseRequestFromArray(array $data): ServiceResponse
    {
        $response = new ServiceResponse($data);
        $expectedSignature = SignatureHelper::calculateSignature(
            $this->getFieldForSignature($response),
            $this->credential->getSecret()
        );

        if (!isset($data["merchantSignature"]) || $expectedSignature !== $data["merchantSignature"]
        ) {
            throw new SignatureException($expectedSignature, $data["merchantSignature"] ?? "");
        }

        return $response;
    }

    public function getSuccessResponse(TransactionService $transaction): array
    {
        $time = time();
        $fields = [
            $transaction->getOrderReference(),
            self::STATUS_ACCEPT,
            $time
        ];
        $signature = SignatureHelper::calculateSignature($fields, $this->credential->getSecret());

        return [
            "orderReference" => $transaction->getOrderReference(),
            "status" => self::STATUS_ACCEPT,
            "time" => $time,
            "signature" => $signature
        ];
    }
}