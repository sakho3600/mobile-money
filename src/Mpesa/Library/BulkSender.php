<?php

namespace DervisGroup\Pesa\Mpesa\Library;

/**
 * Class BulkSender
 *
 * @package DervisGroup\Pesa\Mpesa\Library
 */
class BulkSender extends ApiCore
{
    /**
     * @var string
     */
    private $number;
    /**
     * @var int
     */
    private $amount;


    /**
     * Set number to receive the funds
     *
     * @param  string $number
     * @return $this
     */
    public function to($number)
    {
        $this->number = $this->formatPhoneNumber($number);
        return $this;
    }

    /**
     * The amount to transact
     *
     * @param  $amount
     * @return $this
     */
    public function amount($amount)
    {
        $this->amount = $amount;
        return $this;
    }

    /**
     * @param string|null $number
     * @param int|null    $amount
     * @return mixed
     * @throws \DervisGroup\Pesa\Exceptions\MpesaException
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function send($number = null, $amount = null)
    {
        $body = [
            'InitiatorName' => config('pesa.bulk.initiator'),
            'SecurityCredential' => config('pesa.bulk.security_credential'),
            'CommandID' => 'PromotionPayment', //SalaryPayment,BusinessPayment,PromotionPayment
            'Amount' => $amount ?: $this->amount,
            'PartyA' => config('pesa.bulk.short_code'),
            'PartyB' => $this->formatPhoneNumber($number ?: $this->number),
            'Remarks' => 'Some Remarks',
            'QueueTimeOutURL' => config('pesa.bulk.timeout_url') . 'b2c',
            'ResultURL' => config('pesa.bulk.result_url') . 'b2c',
            'Occasion' => ' '
        ];
        $this->bulk = true;
        return $this->sendRequest($body, 'b2c');
    }

    /**
     * @return mixed
     * @throws \DervisGroup\Pesa\Exceptions\MpesaException
     * @throws \Exception
     * @throws \GuzzleHttp\Exception\GuzzleException
     */
    public function balance()
    {
        $body = [
            'CommandID' => 'AccountBalance',
            'Initiator' => config('pesa.bulk.initiator'),
            'SecurityCredential' => config('pesa.bulk.security_credential'),
            'PartyA' => config('pesa.bulk.short_code'),
            'IdentifierType' => 4,
            'Remarks' => 'Checking Balance',
            'QueueTimeOutURL' => config('pesa.bulk.timeout_url') . 'bulk_balance',
            'ResultURL' => config('pesa.bulk.result_url') . 'bulk_balance',
        ];
        $this->bulk = true;
        return $this->sendRequest($body, 'account_balance');
    }
}