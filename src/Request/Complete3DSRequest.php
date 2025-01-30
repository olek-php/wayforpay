<?php

namespace Olek\WayForPay\Request;

use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Response\Complete3DSResponse;

class Complete3DSRequest extends ApiRequest
{
    public function __construct(
        Credential $credential,
        private readonly string $authTicket,
        private readonly string $d3Md,
        private readonly string $d3Pares
    ) {
        parent::__construct($credential);
    }

    protected function getType(): string
    {
        return "COMPLETE_3DS";
    }

    protected function getTransactionData(): array
    {
        return [
            "apiVersion" => self::API_VERSION,
            "transactionType" => $this->getType(),
            "authorization_ticket" => $this->authTicket,
            "d3ds_md" => $this->d3Md,
            "d3ds_pares" => $this->d3Pares,
        ];
    }

    public function send(): Complete3DSResponse
    {
        $data = $this->sendRequest();
        return new Complete3DSResponse($data);
    }
}