<?php

namespace OpenSRS\trust;

use OpenSRS\Base;
use OpenSRS\Exception;

/*
 *  Required object values:
 *  data - 
 */

class UpdateOrder extends Base
{
    private $_dataObject;
    private $_formatHolder = '';
    public $resultFullRaw;
    public $resultRaw;
    public $resultFullFormatted;
    public $resultFormatted;

    public function __construct($formatString, $dataObject)
    {
        parent::__construct();
        $this->_dataObject = $dataObject;
        $this->_formatHolder = $formatString;
        $this->_validateObject();
    }

    public function __destruct()
    {
        parent::__destruct();
    }

    private function _validateObject()
    {
        $allPassed = true;

        if (!isset($this->_dataObject->data->order_id)) {
            throw new Exception('oSRS Error - order_id is not defined.');
            $allPassed = false;
        }

        // Run the command
        if ($allPassed) {
            // Execute the command
            $this->_processRequest();
        } else {
            throw new Exception('oSRS Error - Incorrect call.');
        }
    }

    // Post validation functions
    private function _processRequest()
    {
        $cmd = array(
            'protocol' => 'XCP',
            'action' => 'update_order',
            'object' => 'trust_service',
            'attributes' => array(
                'order_id' => $this->_dataObject->data->order_id,
            ),
        );

        // Command optional values
        if (isset($this->_dataObject->data->product_type) && $this->_dataObject->data->product_type != '') {
            $cmd['attributes']['product_type'] = $this->_dataObject->data->product_type;
        }
        // reg_type => SiteLock ONLY
        if (isset($this->_dataObject->data->reg_type) && $this->_dataObject->data->reg_type != '') {
            $cmd['attributes']['reg_type'] = $this->_dataObject->data->reg_type;
        }

        if (isset($this->_dataObject->data->special_instructions) && $this->_dataObject->data->special_instructions != '') {
            $cmd['attributes']['special_instructions'] = $this->_dataObject->data->special_instructions;
        }
        if (isset($this->_dataObject->data->server_type) && $this->_dataObject->data->server_type != '') {
            $cmd['attributes']['server_type'] = $this->_dataObject->data->server_type;
        }
        if (isset($this->_dataObject->data->period) && $this->_dataObject->data->period != '') {
            $cmd['attributes']['period'] = $this->_dataObject->data->period;
        }
        if (isset($this->_dataObject->data->approver_email) && $this->_dataObject->data->approver_email != '') {
            $cmd['attributes']['approver_email'] = $this->_dataObject->data->approver_email;
        }
        if (isset($this->_dataObject->data->csr) && $this->_dataObject->data->csr != '') {
            $cmd['attributes']['csr'] = $this->_dataObject->data->csr;
        }
        if (isset($this->_dataObject->data->server_count) && $this->_dataObject->data->server_count != '') {
            $cmd['attributes']['server_count'] = $this->_dataObject->data->server_count;
        }

        $xmlCMD = $this->_opsHandler->encode($cmd);                    // Flip Array to XML
        $XMLresult = $this->send_cmd($xmlCMD);                        // Send XML
        $arrayResult = $this->_opsHandler->decode($XMLresult);        // Flip XML to Array

        // Results
        $this->resultFullRaw = $arrayResult;
        if (isset($arrayResult['attributes'])) {
            $this->resultRaw = $arrayResult['attributes'];
        } else {
            $this->resultRaw = $arrayResult;
        }
        $this->resultFullFormatted = $this->convertArray2Formatted($this->_formatHolder, $this->resultFullRaw);
        $this->resultFormatted = $this->convertArray2Formatted($this->_formatHolder, $this->resultRaw);
        $this->XMLresult = $XMLresult;
    }
}