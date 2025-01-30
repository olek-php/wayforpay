<?php

namespace Olek\WayForPay\Response;

final readonly class InvoiceResponse extends Response
{
	private string $invoiceUrl;
	private string $qrCode;

	public function __construct(array $data)
	{
		parent::__construct($data);

        $this->qrCode = $data["qrCode"];
        $this->invoiceUrl = $data["invoiceUrl"];
	}

	/**
	 * @return string
	 */
	public function getInvoiceUrl(): string
	{
		return $this->invoiceUrl;
	}

	/**
	 * @return string
	 */
	public function getQrCode(): string
	{
		return $this->qrCode;
	}
}
