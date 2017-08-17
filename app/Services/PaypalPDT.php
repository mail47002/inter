<?php

namespace App\Services;

use Illuminate\Http\Request;

class PaypalPDT
{
    const VERIFY_URI = 'https://www.paypal.com/cgi-bin/webscr';
    const VERIFY_SANDBOX_URI ='https://www.sandbox.paypal.com/cgi-bin/webscr';

    private $sandbox = false;

    private $data = [];

    public function useSandbox()
    {
        $this->sandbox = true;
    }

    public function getUri()
    {
        if ($this->sandbox) {
            return self::VERIFY_SANDBOX_URI;
        }

        return self::VERIFY_URI;
    }

    public function verify(Request $request)
    {
        if (!$request->tx) {
            throw new \Exception('Missing payment data');
        }

        $req = 'cmd=_notify-synch';
        $req .= '&tx=' . $request->tx;
        $req .= '&at=' . config('paypal.token');

        $ch = curl_init($this->getUri());

        curl_setopt($ch, CURLOPT_URL, $this->getUri());
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $req);

        $res = curl_exec($ch);

        curl_close($ch);

        if ($res) {
            $lines = explode("\n", trim($res));

            if (strcmp($lines[0], 'SUCCESS') == 0) {
                for ($i = 1; $i < count($lines); $i++) {
                    $temp = explode('=', $lines[$i],2);

                    $data[urldecode($temp[0])] = urldecode($temp[1]);
                }

                $this->data = $data;

                return true;
            }
        }

        return false;
    }

    public function getPaymentData()
    {
        return $this->data;
    }
}