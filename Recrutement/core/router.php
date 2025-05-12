<?php
class Router {
    public function handleRequest() {
        $controllerName = $_GET['controller'] ?? 'auth';
        $action         = $_GET['action'] ?? 'login';

        $controllerClass = ucfirst($controllerName) . 'Controller';
        $controllerFile = 'controllers/' . $controllerClass . '.php';

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $controller = new $controllerClass();
            if (method_exists($controller, $action)) {
                $controller->$action();
            } else {
                echo "Action non trouvée.";
            }
        } else {
            echo "Contrôleur non trouvé.";
        }
    }
}
