<?php

namespace RnTorm\LaravelMargn;

use Exception;

class MargnCustomer extends Margn
{
    private $requestUrl = '/customers/v2';

    public function get($id)
    {
        return $this->httpGet($this->requestUrl.'/'.$id);
    }

    public function add($data)
    {
        if (empty($data)) {
            throw new Exception('No data');
        }

        return $this->httpPost($this->requestUrl, $data);
    }

    public function update($id, $data)
    {
        $data['id'] = $id;

        return $this->httpPut($this->requestUrl.'/'.$id, $data);
    }
}
