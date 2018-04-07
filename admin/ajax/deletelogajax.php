<?php
/**
* AJAX page for user deletion in userverification.php
**/
require '../../login/autoload.php';
try {
    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;

    if ($request->valid_token() && $auth->isAdmin()) {
        try {
            $singleId = $_POST['logid'];

            //Deletes log
            $response = MailHandler::deleteLog($singleId, false);

            //Success
            if ($response == 1) {
                echo $response;
            } else {
                //Validation error from empty form variables
                throw new Exception("Failure");
            }
        } catch (Exception $ex) {
            echo $ex->getMessage();
        }
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    echo $e->getMessage();
}
