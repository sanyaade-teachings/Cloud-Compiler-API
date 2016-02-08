<?php
    namespace API\Submissions;

    require_once __DIR__ . '/../../../include/php/autoload.php';

    function Response($id = null) {
        if($id != null) {
            $output = \Ideone::getOutput($id);
            if(is_array($output)) {
                $response = $output;
                if(isset($_GET['withSource']) && $_GET['withSource'] == 1) {
                    $sourceCode = \Ideone::getSourceCode($id);
                    if(is_string($sourceCode)) {
                        $response['sourceCode'] = $sourceCode;
                    } else {
                        $errorCode = $sourceCode;
                        if($errorCode == \Ideone::CURL_ERROR || $errorCode == \Ideone::SCRAPE_ERROR || $errorCode == \Ideone::LOGIN_ERROR || $errorCode == \Ideone::REDIRECTION_ERROR) {
                            $error = 'SYSTEM_ERROR';
                            $errorDesc = 'Some system error occurred, Please try again.';
                        } else if($errorCode == \Ideone::INVALID_SUBM_ID) {
                            $error = 'INVALID_SUBM_ID';
                            $errorDesc = 'Invalid input for the submission id.';
                        } else {
                            $error = 'UNKNOWN_ERROR';
                            $errorCode = \Ideone::UNKNOWN_ERROR;
                            $errorDesc = 'Some unknown error occurred, Please try again.';
                        }
                        $response = array(
                            'error' => $error,
                            'errorCode' => $errorCode,
                            'errorDesc' => $errorDesc
                        );
                    }
                }
                if(isset($_GET['withInput']) || isset($_GET['withLang']) || isset($_GET['withTimestamp'])) {
                    $input = \Ideone::getInputData($id);
                    if(is_array($input)) {
                        if(isset($_GET['withInput']) && $_GET['withInput'] == 1) {
                            $response['stdin'] = $input['stdin'];
                        }
                        if(isset($_GET['withLang']) && $_GET['withLang'] == 1) {
                            $response['langId'] = $input['langId'];
                            $response['langName'] = $input['langName'];
                            $response['langVersion'] = $input['langVersion'];
                        }
                        if(isset($_GET['withTimestamp']) && $_GET['withTimestamp'] == 1) {
                            $response['timestamp'] = $input['timestamp'];
                        }
                    } else {
                        $errorCode = $input;
                        if($errorCode == \Ideone::CURL_ERROR || $errorCode == \Ideone::SCRAPE_ERROR || $errorCode == \Ideone::LOGIN_ERROR || $errorCode == \Ideone::REDIRECTION_ERROR) {
                            $error = 'SYSTEM_ERROR';
                            $errorDesc = 'Some system error occurred, Please try again.';
                        } else if($errorCode == \Ideone::INVALID_SUBM_ID) {
                            $error = 'INVALID_SUBM_ID';
                            $errorDesc = 'Invalid input for the submission id.';
                        } else {
                            $error = 'UNKNOWN_ERROR';
                            $errorCode = \Ideone::UNKNOWN_ERROR;
                            $errorDesc = 'Some unknown error occurred, Please try again.';
                        }
                        $response = array(
                            'error' => $error,
                            'errorCode' => $errorCode,
                            'errorDesc' => $errorDesc
                        );
                    }
                }
            } else {
                $errorCode = $output;
                if($errorCode == \Ideone::CURL_ERROR || $errorCode == \Ideone::SCRAPE_ERROR || $errorCode == \Ideone::LOGIN_ERROR || $errorCode == \Ideone::REDIRECTION_ERROR) {
                    $error = 'SYSTEM_ERROR';
                    $errorDesc = 'Some system error occurred, Please try again.';
                } else if($errorCode == \Ideone::INVALID_SUBM_ID) {
                    $error = 'INVALID_SUBM_ID';
                    $errorDesc = 'Invalid input for the submission id.';
                } else {
                    $error = 'UNKNOWN_ERROR';
                    $errorCode = \Ideone::UNKNOWN_ERROR;
                    $errorDesc = 'Some unknown error occurred, Please try again.';
                }
                $response = array(
                    'error' => $error,
                    'errorCode' => $errorCode,
                    'errorDesc' => $errorDesc
                );
            }
        } else {
            if(isset($_POST['sourceCode']) && isset($_POST['langId'])) {
                $sourceCode = $_POST['sourceCode'];
                $langId = $_POST['langId'];
                if(isset($_POST['stdin'])) {
                    $stdin = $_POST['stdin'];
                } else {
                    $stdin = '';
                }
                if(isset($_POST['timeLimit'])) {
                    $time = $_POST['timeLimit'];
                } else {
                    $time = 0;
                }
                $result = \Ideone::getID($sourceCode, $langId, $stdin, $time);
                if(is_string($result)) {
                    $response = array(
                        'id' => $result,
                    );
                } else {
                    $errorCode = $result;
                    if($errorCode == \Ideone::CURL_ERROR || $errorCode == \Ideone::SCRAPE_ERROR || $errorCode == \Ideone::LOGIN_ERROR) {
                        $error = 'SYSTEM_ERROR';
                        $errorDesc = 'Some system error occurred, Please try again.';
                    } else if($errorCode == \Ideone::INVALID_TIME_INPUT) {
                        $error = 'INVALID_TIME_INPUT';
                        $errorDesc = 'Invalid input for timeLimit.';
                    } else if($errorCode == \Ideone::INVALID_LANG_ID) {
                        $error = 'INVALID_LANG_ID';
                        $errorDesc = 'Invalid input for langId.';
                    } else {
                        $error = 'UNKNOWN_ERROR';
                        $errorCode = \Ideone::UNKNOWN_ERROR;
                        $errorDesc = 'Some unknown error occurred, Please try again.';
                    }
                    $response = array(
                        'error' => $error,
                        'errorCode' => $errorCode,
                        'errorDesc' => $errorDesc
                    );
                }
            } else {
                if(isset($_POST['sourceCode'])) {
                    $response = array(
                        'error' => 'LANGID_UNDEFINED',
                        'errorCode' => \Ideone::LANGID_UNDEFINED,
                        'errorDesc' => 'Language ID is not provided in the input.'
                    );
                } else {
                    $response = array(
                        'error' => 'SOURCECODE_UNDEFINED',
                        'errorCode' => \Ideone::SOURCECODE_UNDEFINED,
                        'errorDesc' => 'Source code is not provided in the input.'
                    );
                }
            }
        }

        return $response;
    }