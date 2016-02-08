<?php
    namespace API\Languages\Sample;

    require_once __DIR__ . '/../../../../include/php/autoload.php';

    function Response($id) {
        $result = \Ideone::getSample($id);
        if(is_array($result)) {
            $response = $result;
        } else {
            $errorCode = $result;
            if($errorCode == \Ideone::CURL_ERROR || $errorCode == \Ideone::SCRAPE_ERROR || $errorCode == \Ideone::LOGIN_ERROR || $errorCode == \Ideone::REDIRECTION_ERROR) {
                $error = 'SYSTEM_ERROR';
                $errorDesc = 'Some system error occurred, Please try again.';
            } else if($errorCode == \Ideone::INVALID_LANG_ID) {
                $error = 'INVALID_LANG_ID';
                $errorDesc = 'Invalid input for langId.';
            } else if($errorCode == \Ideone::NO_SAMPLE_AVAILABLE) {
                $error = 'NO_SAMPLE_AVAILABLE';
                $errorDesc = 'No sample code available for the given language.';
            } else {
                $error = $result;
                $errorCode = \Ideone::UNKNOWN_ERROR;
                $errorDesc = 'Some unknown error occurred, Please try again.';
            }
            $response = array(
                'error' => $error,
                'errorCode' => $errorCode,
                'errorDesc' => $errorDesc
            );
        }

        return $response;
    }