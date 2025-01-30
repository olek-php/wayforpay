<?php

namespace Olek\WayForPay\Request;

use DateTimeInterface;
use Olek\WayForPay\Credential\Credential;
use Olek\WayForPay\Response\TransactionListResponse;

class TransactionListRequest extends ApiRequest
{
    public function __construct(
        Credential $credential,
        private readonly DateTimeInterface $dateBegin,
        private readonly DateTimeInterface $dateEnd,
    ) {
        parent::__construct($credential);
    }

    protected function getRequestSignatureFieldsValues(): array
    {
        return array_merge(parent::getRequestSignatureFieldsValues(), [
            "dateBegin" => $this->dateBegin->getTimestamp(),
            "dateEnd" => $this->dateEnd->getTimestamp(),
        ]);
    }

    protected function getType(): string
    {
        return "TRANSACTION_LIST";
    }

    protected function getTransactionData(): array
    {
        return array_merge(parent::getTransactionData(), [
            "dateBegin" => $this->dateBegin->getTimestamp(),
            "dateEnd" => $this->dateEnd->getTimestamp()
        ]);
    }

    public function send(): TransactionListResponse
    {
        $data = $this->sendRequest();
        return new TransactionListResponse($data);
    }
}