<?php
session_start();

// Chargement automatique des classes (models et controllers)
spl_autoload_register(function ($class) {
    $paths = [
        "../app/controllers/$class.php",
        "../app/models/$class.php"
    ];
    foreach ($paths as $file) {
        if (file_exists($file)) {
            require_once $file;
            return;
        }
    }
});

// Définir la page et l'action depuis l'URL
$page   = $_GET['page'] ?? 'home';
$action = $_GET['action'] ?? 'index';

// Routing simple
switch ($page) {
    case 'home':
        require_once "../app/controllers/HomeController.php";
        $controller = new HomeController();
        if(method_exists($controller, $action)){
            $controller->{$action}();
        } else {
            $controller->index();
        }
        break;

    case 'product':
    require_once "../app/controllers/ProductController.php";
    $controller = new ProductController();
    if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
        $controller->{$_GET['action']}();
    } else {
        // action par défaut
        $controller->show();
    }
    break;

    case 'chatbot':
        require_once "../app/controllers/ChatbotController.php";
        $controller = new ChatbotController();
        if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
            $controller->{$_GET['action']}();
        } else {
            $controller->index();
        }
        break;


    case 'cart':
        require_once "../app/controllers/CartController.php";
        $controller = new CartController();
        if(method_exists($controller, $action)){
            $controller->{$action}();
        } else {
            $controller->index();
        }
        break;

    case 'auth':
        require_once "../app/controllers/AuthController.php";
        $controller = new AuthController();
        if(method_exists($controller, $action)){
            $controller->{$action}();
        } else {
            $controller->login();
        }
        break;

    case 'admin':
        require_once "../app/controllers/AdminController.php";
        $controller = new AdminController();
        if(method_exists($controller, $action)){
            $controller->{$action}();
        } else {
            $controller->dashboard();
        }
        break;

    default:
        echo "<h1>Page non trouvée (404)</h1>";
        break;
}
