<?php


namespace rollun\Entity\Usps;

use \USPS\PriorityLabel as Label;
use rollun\Entity\Usps\LabelData;

class PriorityLabel
{


    const KEY_RESULT_ERORR = 'Error';
    const KEY_RESULT_LABEL = 'Label';

    /**
     * @var LabelData
     * */
    public $labelData = null;


    public function __construct(LabelData $labelData = null)
    {
        $this->labelData = $labelData;
    }

    /**
     * @param \rollun\Entity\Usps\LabelData|null $labelData
     * @return string
     */
    public function getPriorityLabel(LabelData $labelData = null)
    {
        $labelData = $labelData ?? $this->labelData;

        if (empty($labelData)) {
            $result[self::KEY_RESULT_ERORR] = 'Undefined Label data';
            return $result;
        }

        $label = $this->getLabel($labelData);
        $label->createLabel();

        $apiResponse = $label->getArrayResponse();
        if (array_key_exists('Error', $apiResponse)) {
            $result[self::KEY_RESULT_ERORR] = $apiResponse['Error'] ['Description'];
            return $result;
        }

        if ($label->isSuccess()) {
            $result['Confirmation'] = $label->getConfirmationNumber();
            $result[self::KEY_RESULT_LABEL] = $label->getLabelContents();
            if ($result[self::KEY_RESULT_LABEL]) {
                $result['headers'] = ['Content-type: application/pdf',
                    'Content-Disposition: inline; filename="label.pdf"',
                    'Content-Transfer-Encoding: binary',
                    'Content-Length: ' . strlen(base64_decode($result[self::KEY_RESULT_LABEL]))
                ];
            }
            return $result;
        } else {
            $result[self::KEY_RESULT_ERORR] = $label->getErrorMessage();
            return $result;
        }
    }

    /**
     * @param $labelData
     * @return Label
     */
    public function getLabel(LabelData $labelData): Label
    {
        $label = new Label(getenv('USPS_API_PASS'));
        foreach (LabelData::FIELDS as $position => $key) {
            $label->setField($position, $key, $labelData[$key]);
        }
        return $label;
    }
}

