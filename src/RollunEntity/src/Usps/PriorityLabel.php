<?php


namespace rollun\Entity\Usps;

use \USPS\PriorityLabel;

class Label
{
    /**
     * @var string - the api version used for this type of call
     */
    protected $apiVersion = 'ExpressMailLabel';
    /**
     * @var array - from addresses
     */
    protected $from = [];

    /**
     * @var array - to addresses
     */
    protected $to = [];

    /**
     * @var array - route added so far.
     */
    protected $fields = [];

    const KEY_RESULT_ERORR = 'Error';
    const KEY_RESULT_PRICE = 'Price';

    public function __construct($username = '')
    {
       
    }

    public function create(array $from, array $to)
    {
        $this->setFromAddress(
            $from['firstName'],
            $from['lastName'],
            $from['company'],
            $from['address'],
            $from['city'],
            $from['state'],
            $from['zip'],
            $from['address2'] ?? null,
            $from['zip4'] ?? null);
        $this->setToAddress(
            $to['company'],
            $to['address'],
            $to['city'],
            $to['state'],
            $to['zip'],
            $from['address2'] ?? null,
            $from['zip4'] ?? null);

        return $this->createLabel();
    }
}

// Initiate and set the username provided from usps
$label = new PriorityLabel('xxxx');
$label->setFromAddress('John', 'Doe', '', '5161 Lankershim Blvd', 'North Hollywood', 'CA', '91601', '# 204', '', '8882721214');
$label->setToAddress('Vincent', 'Gabriel', '', '230 Murray St', 'New York', 'NY', '10282');

$label->setWeightOunces(1);
// Perform the request and return result
$label->createLabel();
//print_r($label->getArrayResponse());
print_r($label->getPostData());
//var_dump($label->isError());
// See if it was successful
if ($label->isSuccess()) {
    echo 'Done';
    echo "\n Confirmation:" . $label->getConfirmationNumber();
    $label = $label->getLabelContents();
    if ($label) {
        $contents = base64_decode($label);
        header('Content-type: application/pdf');
        header('Content-Disposition: inline; filename="label.pdf"');
        header('Content-Transfer-Encoding: binary');
        header('Content-Length: ' . strlen($contents));
        echo $contents;
        exit;
    }
} else {
    echo 'Error: ' . $label->getErrorMessage();
}