<?php

namespace RnTorm\LaravelMargn;

class MargnCustomer extends Margn
{
    private $requestUrl = '/customers';

    public function __construct()
    {
        parent::__construct();
    }

    // Requires id
    public function get($id)
    {
        $get_response = $this->httpGet($this->requestUrl.'/'.$id);

        return $get_response;
    }

    public function add($data)
    {
        if (empty($data)) {
            throw new Exception('No data');
        }

        $post_response = $this->httpPost($this->requestUrl, $data);

        return $post_response;
    }

    public function update($id, $data)
    {
        $data['id'] = $id;
        $this->httpPut($this->requestUrl.'/'.$id, $data);
    }
}
