<?php
//check if session is not started and start it
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}



function prepare_phone_number($phone_number)
{
    $original = $phone_number;
    //$phone_number = '+256783204665';
    //0783204665
    if (strlen($phone_number) > 10) {
        $phone_number = str_replace("+", "", $phone_number);
        $phone_number = substr($phone_number, 3, strlen($phone_number));
    } else {
        if (substr($phone_number, 0, 1) == "0") {
            $phone_number = substr($phone_number, 1, strlen($phone_number));
        }
    }
    if (strlen($phone_number) != 9) {
        return $original;
    }
    return "+256" . $phone_number;
}

function phone_number_is_valid($phone_number)
{
    $phone_number = prepare_phone_number($phone_number);
    if (substr($phone_number, 0, 4) != "+256") {
        return false;
    }

    if (strlen($phone_number) != 13) {
        return false;
    }

    return true;
}


function depositFunds($msisdn, $amount, $narrative, $reference = null)
{
    $msisdn = str_replace("+", "", $msisdn);
    $xml = '';
    $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<AutoCreate>';
    $xml .= '<Request>';
    $xml .= '<APIUsername>' . '100157178257' . '</APIUsername>';
    $xml .= '<APIPassword>' . 'aYmL-ITHt-MGhm-U8P3-w4yS-OIEk-aoWC-vMoc' . '</APIPassword>';
    $xml .= '<Method>acdepositfunds</Method>';
    $xml .= '<NonBlocking>TRUE</NonBlocking>';
    $xml .= '<Account>' . $msisdn . '</Account>';
    $xml .= '<Amount>' . $amount . '</Amount>';
    $xml .= '<Narrative>' . $narrative . '</Narrative>';

    if ($reference != NULL) {
        $xml .= '<ExternalReference>' . $reference . '</ExternalReference>';
    } else {
        $ref = 'Deposit' . rand(100000, 999999);
        $xml .= '<ExternalReference>' . $ref . '</ExternalReference>';
    }

    $xml .= '</Request>';
    $xml .= '</AutoCreate>';



    //get_xml_response
    $response = curl_init();




    curl_setopt($response, CURLOPT_URL, "https://paymentsapi1.yo.co.ug/ybs/task.php");
    curl_setopt($response, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($response, CURLOPT_POST, 1);
    curl_setopt($response, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($response, CURLOPT_HTTPHEADER, array(
        'Content-Type: text/xml',
    ));

    $xml_response = curl_exec($response);
    curl_close($response);
    echo '<pre>';
    print_r(($xml_response));
    echo "<hr>";
    print_r(json_decode($xml_response));
    echo '</pre>';
    die("done");
    return $xml_response;


    // $xml_response = curl_exec($xml_response);
    // curl_close($xml_response);

    $simpleXMLObject =  new SimpleXMLElement($xml_response);
    $response = $simpleXMLObject->Response;

    $result = (object) [];
    $result->Status = (string) $response->Status;
    $result->StatusCode = (string) $response->StatusCode;
    $result->StatusMessage = (string) $response->StatusMessage;
    $result->TransactionStatus = (string) $response->TransactionStatus;

    if (!empty($response->ErrorMessageCode)) {
        $result->ErrorMessageCode = (string) $response->ErrorMessageCode;
    }
    if (!empty($response->ErrorMessage)) {
        $result->ErrorMessage = (string) $response->ErrorMessage;
    }
    if (!empty($response->TransactionReference)) {
        $result->TransactionReference = (string) $response->TransactionReference;
    }
    if (!empty($response->MNOTransactionReferenceId)) {
        $result->MNOTransactionReferenceId = (string) $response->MNOTransactionReferenceId;
    }
    if (!empty($response->IssuedReceiptNumber)) {
        $result->IssuedReceiptNumber = (string) $response->IssuedReceiptNumber;
    }

    return $result;
}
