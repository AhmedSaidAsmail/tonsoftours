<?php

namespace App\Src\Payment\Paypal;


use App\Src\Payment\Exception\NoSettingFoundException;
use App\Src\Payment\Exception\PaymentException;
use App\Src\Payment\PaymentGateway;
use Illuminate\Http\Request;
use App\Models\Paypal as PaypalModel;

class Paypal implements PaymentGateway
{
    /**
     * @var boolean $sandbox Paypal sandbox
     */
    public $sandbox;
    /**
     * @var string $client_id Paypal account id
     */
    public $client_id;
    /**
     * @var string $secret Paypal secret id
     */
    public $secret;
    /**
     * @var string $currency Paypal default currency
     */
    public $currency;
    /**
     * @var int $total
     */
    public $total;
    /**
     * @var Request $request
     */
    public $request;
    /**
     * @var string $description
     */
    public $description;
    /**
     * @var string $successLink Success link redirect
     */
    public $successLink;
    /**
     * @var string $failureLink Failure link redirect
     */
    public $failureLink;

    /**
     * Paypal constructor.
     * @param Request $request
     * @param $total
     * @param $successLink
     * @param $failureLink
     */
    public function __construct(Request $request, $total, $successLink, $failureLink)
    {
        $this->request = $request;
        $this->total = $total;
        $this->successLink = $successLink;
        $this->failureLink = $failureLink;
    }

    /**
     * @return void
     * @throws NoSettingFoundException
     */
    public function init()
    {
        if ($this->checkModelSetting()) {
            $this->setPaypalSetting();
            return;
        }
        throw new NoSettingFoundException('No setting found for 2Checkout Payment Gateway in your Database');
    }

    /**
     * @return bool
     */
    private function checkModelSetting()
    {
        return null !== PaypalModel::first();
    }

    private function setPaypalSetting()
    {
        $setting = PaypalModel::first();
        $this->sandbox = $setting->sandbox;
        $this->client_id = $setting->client_id;
        $this->secret = $setting->secret;
        $this->currency = $setting->currency;
        $this->description = $setting->description;
    }

    /**
     * @return string
     * @throws PaymentException
     */
    public function pay()
    {
        $paypal = new PaypalApiBuilder($this);
        try {
            $paypal->build();
        } catch (\Exception $e) {
            throw new PaymentException($e->getMessage());
        }
    }
}