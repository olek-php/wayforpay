<?php
/*
 * This file is part of the WayForPay project.
 *
 * @link https://github.com/wayforpay/php-sdk
 *
 * @author Vladislav Lyshenko <vladdnepr1989@gmail.com>
 * @copyright Copyright 2019 WayForPay
 * @license   https://opensource.org/licenses/MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Olek\WayForPay\Credential;

use Olek\WayForPay\Contract\CredentialInterface;

final readonly class Credential implements CredentialInterface
{
    public function __construct(
        private string $account,
        private string $secret,
    ) {
    }

    public function getAccount(): string
    {
        return $this->account;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }
}