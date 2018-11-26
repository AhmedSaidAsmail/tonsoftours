<?php

namespace App\Src\Payment\TwoCheckOut;


use App\Src\Payment\Exception\NoArgumentGivenException;

class Seller
{
    private $fillable = [
        'sellerId', 'merchantOrderId', 'token', 'currency', 'total', 'billingAddr', 'shippingAddr'
    ];
    /**
     * @var string $sellerId
     */
    private $sellerId;
    /**
     * @var int $merchantOrderId
     */
    private $merchantOrderId;
    /**
     * @var string $token
     */
    private $token;
    /**
     * @var string $currency
     */
    private $currency;
    /**
     * @var int $total
     */
    private $total;
    /**
     * @var array $billingAddr
     */
    private $billingAddr;
    /**
     * @var array $shippingAddr
     */
    private $shippingAddr;

    /**
     * Seller constructor.
     * @param CheckOut $checkOut
     */
    public function __construct(CheckOut $checkOut)
    {
        $this->sellerId = $checkOut->sellerId;
        $this->merchantOrderId = md5(uniqid(rand(), true));
        $this->currency = $checkOut->currency;
        $this->token = $checkOut->request->get('token');
        $this->total = $checkOut->total;
        $this->shippingAddr = $checkOut->shippingAddr->__toArray();
        $this->billingAddr = $checkOut->billingAddr->__toArray();


    }

    /**
     * @return array
     */
    public function __toArray()
    {

        $return = [];
        array_filter($this->fillable, function ($field) use (&$return) {
            if (property_exists(self::class, $field) && !is_null($this->{$field})) {
                $return[$field] = $this->{$field};
            } else {
                throw new NoArgumentGivenException(sprintf('Can not find the %s key in given data', $field));
            }
        });
        return $return;

    }

}