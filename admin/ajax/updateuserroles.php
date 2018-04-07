<?php
require '../../login/autoload.php';

try {
    session_start();

    $request = new CSRFHandler;
    $auth = new AuthorizationHandler;
    $rolehandler = new RoleHandler;

    if ($request->valid_token() && $auth->isAdmin()) {
        try {
            $roles = json_decode($_POST['formData']);
            $user_id = $_POST['userId'];

            if (property_exists($roles[0], '0')) {
                $rolehandler->unassignAllRoles($user_id);

                foreach ($roles[0] as $role) {
                    $rolehandler->assignRole($role->role_id, $user_id);
                }
            } else {
                echo 'false';
                return;
            }

            echo json_encode($roles);
            return;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    } else {
        http_response_code(401);
        throw new Exception("Unauthorized");
    }
} catch (Exception $e) {
    error_log($e->getMessage());
    echo json_encode($e->getMessage());
}
