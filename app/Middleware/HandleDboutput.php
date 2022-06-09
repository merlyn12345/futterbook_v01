<?php

namespace App\Middleware;

class HandleDboutput
{
    protected array $dbdata;

    public function __construct()
    {
        $this->dbdata = array();
    }

    public function setDbdata(array $dbdata){
        if($dbdata){
            $this->dbdata = $dbdata;
            return true;
        }
        return false;
    }

    public function renderdata(): array
    {
        foreach($this->dbdata as $key => $food){
            $amount = (int)$food['amount'];
            $energieinhalt = (int)$food['Energie (cal)'];
            $sum = ($amount * $energieinhalt)/100;
            $energie = array("energie" => $sum);
            $food = array_merge($food, $energie);
            $this->dbdata[$key]=$food;
        }


        return $this->dbdata;
    }
}