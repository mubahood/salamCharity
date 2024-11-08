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
    $xml = '';
    $xml .= '<?xml version="1.0" encoding="UTF-8"?>';
    $xml .= '<AutoCreate>';
    $xml .= '<Request>';
    $xml .= '<APIUsername>' . '100157178257' . '</APIUsername>';
    $xml .= '<APIPassword>' . 'aYmL-ITHt-MGhm-U8P3-w4yS-OIEk-aoWC-vMoc' . '</APIPassword>';
    $xml .= '<Method>acdepositfunds</Method>';
    $xml .= '<NonBlocking>TRUE</NonBlocking>';
    $xml .= '<Account>' . '256783204665' . '</Account>';
    $xml .= '<Amount>' . '1000' . '</Amount>';
    $xml .= '<Narrative>' . "simple message" . '</Narrative>';
    /*
    <AutoCreate>
        <Request>
            <APIUsername></APIUsername>
            <APIPassword></APIPassword>
            <Method>acdepositfunds</Method>
            <NonBlocking></NonBlocking>
            <Amount></Amount>
            <Account></Account>
            <AccountProviderCode></AccountProviderCode>
            <Narrative></Narrative>
            <NarrativeFileName></NarrativeFileName>
            <NarrativeFileBase64></NarrativeFileBase64>
            <InternalReference></InternalReference>
            <ExternalReference></ExternalReference>
            <ProviderReferenceText></ProviderReferenceText>
            <InstantNotificationUrl></InstantNotificationUrl>
            <FailureNotificationUrl></FailureNotificationUrl>
            <AuthenticationSignatureBase64></AuthenticationSignatureBase64>
        </Request>
    </AutoCreate>
    */
    if ($reference != NULL) {
        $xml .= '<ExternalReference>' . $reference . '</ExternalReference>';
    }
    // if ($this->internal_reference != NULL) {
    //     $xml .= '<InternalReference>' . $this->internal_reference . '</InternalReference>';
    // }
    // if ($this->provider_reference_text != NULL) {
    //     $xml .= '<ProviderReferenceText>' . $this->provider_reference_text . '</ProviderReferenceText>';
    // }
    // if ($this->instant_notification_url != NULL) {
    //     $xml .= '<InstantNotificationUrl>' . $this->instant_notification_url . '</InstantNotificationUrl>';
    // }
    // if ($this->failure_notification_url != NULL) {
    //     $xml .= '<FailureNotificationUrl>' . $this->failure_notification_url . '</FailureNotificationUrl>';
    // }
    // if ($this->authentication_signature_base64 != NULL) {
    //     $xml .= '<AuthenticationSignatureBase64>' . $this->authentication_signature_base64 . '</AuthenticationSignatureBase64>';
    // }
    $xml .= '</Request>';
    $xml .= '</AutoCreate>';

    //get_xml_response
    $response = curl_init();

    curl_setopt($response, CURLOPT_URL, "https://api.paystack.co/transaction/initialize");
    curl_setopt($response, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($response, CURLOPT_POST, 1);
    curl_setopt($response, CURLOPT_POSTFIELDS, $xml);
    curl_setopt($response, CURLOPT_HTTPHEADER, array(
        'Content-Type: application/xml',
        'Authorization: Bearer sk_live_7c9c8d7e7e3f6b8b7e5f5f5f5f5f5f5f5f5f5f5'
    ));

    $xml_response = curl_exec($response);
    curl_close($response);
    echo '<pre>';
    print_r(json_decode($xml_response));
    echo '</pre>';
    die();


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
