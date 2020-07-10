<?php

namespace RnTorm\LaravelMargn;

use Exception;

class MargnAttribute extends Margn
{
    private $requestUrl = '/attributes';

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
