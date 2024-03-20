<?php

class BankAccount
{
    // properties
    private $balance;
    private $account_name;
    private $account_number;


    // constructor
    public function __construct($name, $number)
    {
        $this->account_name   = $name;
        $this->account_number = $number;
    }


    // getters
    public function get_balance()
    {
        return $this->balance;
    }

    public function get_name()
    {
        return $this->account_name;
    }

    public function get_number()
    {
        return $this->account_number;
    }


    // setters
    public function set_balance($balance)
    {
        $this->balance = $balance;
    }

    public function set_name($name)
    {
        $this->account_name = $name;
    }

    public function set_number($number)
    {
        $this->account_number = $number;
    }

    public function deposit($amount)
    {
        if($amount > 0) {
            $new_balance = $this->balance + $amount;
            $this->set_balance($new_balance);
            return true;
        }
        else {
            return false;
        }
    }

    public function withdraw($amount)
    {
        $new_balance = $this->balance - $amount;
        if($new_balance >= 0) {
            $this->set_balance($new_balance);
            return true;
        }
        else {
            return false;
        }
    }
}