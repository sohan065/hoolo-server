<?php

namespace App\Services;

use Carbon\Carbon;
use App\Repositories\InvoiceRepositoryInterface;

class InvoiceRepositoryServices implements InvoiceRepositoryInterface
{
    private $_keys;
    public function __construct()
    {
        $this->_keys = [
            'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
            'a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
            '0', '1', '2', '3', '4', '5', '6', '7', '8', '9'
        ];
    }
    public function create($userId, $type)
    {
        $date = Carbon::now()->toArray();
        $year = $date['year'];
        $month = $date['month'];
        $day = $date['day'];
        $hour = $date['hour'];
        $minute = $date['minute'];
        $second = $date['second'];

        $invoice = ($type == 'product' ? 'p' : 'w') . $year . $this->_keys[$month] . $this->_keys[$day] . $this->_keys[$hour] . $this->_keys[$minute] . $this->_keys[$second] . $userId;
        return $invoice;
    }
    // public function coc($userId)
    // {
    //     $date = Carbon::now()->toArray();
    //     $year = $date['year'];
    //     $month = $date['month'];
    //     $day = $date['day'];
    //     $hour = $date['hour'];
    //     $minute = $date['minute'];
    //     $second = $date['second'];

    //     $coc = $year . $this->_keys[$month] . $this->_keys[$day] . $this->_keys[$hour] . $this->_keys[$minute] . $this->_keys[$second] . $userId;
    //     return $coc;
    // }

}
