<?php

namespace Application;

class CyberSoapClient extends \CybsSoapClient
{
    function __construct($options = array(), $properties = [])
    {
        if (empty($properties)) {
            $properties = parse_ini_file('cybs.ini');
        }
        parent::__construct($options);
        \CybsClient::__construct($options, $properties);
    }

}