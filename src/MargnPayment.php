<?php

namespace RnTorm\LaravelMargn;

use Carbon\Carbon;

class MargnPayment extends Margn
{
    const PURCHASE_INVOICE_PAYMENT = 'PURCHASE_INVOICE_PAYMENT';
    const INVOICE_PAYMENT = 'INVOICE_PAYMENT';

    private $requestUrl = '/payments';

    public function getByInvoice($invoiceId)
    {
        $this->variables['getEverything'] = true;
        $this->variables['paymentType'] = $type;

        return $this->httpGet($this->requestUrl);
    }

    public function add($data)
    {
        if (empty($data)) {
            throw new Exception('No data');
        }

        if ( ! isset($data['accountId']))
        {
            $data['accountId'] = $this->accountId;
        }

        return $this->httpPost($this->requestUrl, $this->setOpDate($data));
    }

    public function update($id, $data)
    {
        return $this->httpPut($this->requestUrl.'/'.$id, $this->setOpDate($data));
    }

    protected function setOpDate($data)
    {
        if ( ! isset($data['opDate']))
        {
            $data['opDate'] = Carbon::now()->format('Y-m-d\TH:i:s');
        }

        return $data;
    }
}
