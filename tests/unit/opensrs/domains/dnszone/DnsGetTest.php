<?php

use OpenSRS\domains\dnszone\DnsGet;
/**
 * @group dnszone
 * @group DnsGet
 */
class DnsGetTest extends PHPUnit_Framework_TestCase
{
    protected $func = 'dnsGet';

    protected $validSubmission = array(
        'data' => array(
            /**
             * Required
             *
             * domain: domain whose DNS you want
             *   to view
             */
            'domain' => '',
            ),
        );

    /**
     * Valid submission should complete with no
     * exception thrown
     *
     * @return void
     *
     * @group validsubmission
     */
    public function testValidSubmission() {
        $data = json_decode( json_encode($this->validSubmission) );

        $data->data->domain = 'phptest'.time().'.com';

        $ns = new DnsGet( 'array', $data );

        $this->assertTrue( $ns instanceof DnsGet );
    }

    /**
     * Data Provider for Invalid Submission test
     */
    function submissionFields() {
        return array(
            'missing domain' => array('domain'),
            );
    }

    /**
     * Invalid submission should throw an exception
     *
     * @return void
     *
     * @dataProvider submissionFields
     * @group invalidsubmission
     */
    public function testInvalidSubmissionFieldsMissing( $field, $parent = 'data', $message = null ) {
        $data = json_decode( json_encode($this->validSubmission) );

        $data->data->domain = 'phptest'.time().'.com';

        if(is_null($message)){
          $this->setExpectedExceptionRegExp(
              'OpenSRS\Exception',
              "/$field.*not defined/"
              );
        }
        else {
          $this->setExpectedExceptionRegExp(
              'OpenSRS\Exception',
              "/$message/"
              );
        }



        // clear field being tested
        if(is_null($parent)){
            unset( $data->$field );
        }
        else{
            unset( $data->$parent->$field );
        }

        $ns = new DnsGet( 'array', $data );
    }
}