<?php

// ✅ Correct path: Load core classes from app/Core
require_once __DIR__ . "/../app/Core/Router.php";
require_once __DIR__ . "/../app/Core/Database.php";
require_once __DIR__ . "/../app/Core/Session.php";

// ✅ Start session
Session::start();

$router = new Router();

// Define routes
$router->get("/", function() { 
    if (Session::has('user_id')) {
        $role = Session::get('user_role');
        switch ($role) {
            case 'admin':
                header('Location: ' . BASE_URL . '/admin/users');
                break;
            case 'salarie':
                header('Location: ' . BASE_URL . '/salarie/ideas');
                break;
            case 'evaluateur':
                header('Location: ' . BASE_URL . '/evaluateur/ideas');
                break;
            default:
                header('Location: ' . BASE_URL . '/login');
        }
    } else {
        header('Location: ' . BASE_URL . '/login');
    }
    exit;
});

// ✅ Auth routes
$router->get("/login", "AuthController@login");
$router->post("/login", "AuthController@authenticate");
$router->get("/logout", "AuthController@logout");

// ✅ Admin routes
$router->get("/admin/users", "AdminController@manageUsers");
$router->get("/admin/users/create", "AdminController@createUser");
$router->post("/admin/users/store", "AdminController@storeUser");
$router->get("/admin/users/edit/{id}", "AdminController@editUser");
$router->post("/admin/users/update/{id}", "AdminController@updateUser");
$router->post("/admin/users/delete/{id}", "AdminController@deleteUser");

$router->get("/admin/thematiques", "AdminController@manageThematiques");
$router->get("/admin/thematiques/create", "AdminController@createThematique");



$router->get("/admin/ideas", "AdminController@manageIdeas");
$router->get("/admin/viewIdea/{id}", "AdminController@viewIdea");
$router->post("/admin/deleteIdea/{id}", "AdminController@deleteIdea");

// ✅ Salarie routes
$router->get("/salarie/ideas", "SalarieController@myIdeas");
$router->get("/salarie/ideas/submit", "SalarieController@submitIdea");
$router->post("/salarie/ideas/store", "SalarieController@storeIdea");
$router->get("/salarie/ideas/view/{id}", "SalarieController@viewIdea");


// ✅ Evaluateur routes
$router->get("/evaluateur/ideas", "EvaluateurController@ideasToEvaluate");
$router->get("/evaluateur/ideas/evaluate/{id}", "EvaluateurController@evaluateIdea");
$router->post("/evaluateur/ideas/store_evaluation/{id}", "EvaluateurController@storeEvaluation");
$router->get("/evaluateur/ideas/top", "EvaluateurController@topIdeas");
$router->get("/evaluateur/evaluations", "EvaluateurController@myEvaluations");

// ✅ Dispatch
$router->dispatch();