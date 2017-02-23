<?php

class Application
{
    public function run()
    {
        $check = new Check();

        if (!$check->exists($_GET)) {
            //Loading the default page
            $siteController = new SiteController();
            $siteController->index();
            return;
        }

        $arr = $this->getRequest();
        if ($check->exists($arr)) {
            $classController = '';
            $method = '';

            if ($check->exists($arr['controller'])) {
                $classController = $arr['controller'];
            }
            if ($check->exists($arr['method'])) {
                $method = $arr['method'];
            }

            $classController = $this->findController($classController);
            if ($check->exists($classController)) {
                $found = $this->findMethod($classController, $method);
                if ($check->exists($found)) {
                    $run = "
						\$classController = new {$classController}();
						if (in_array('{$method}', \$classController->allowed)) {
							\$classController->{$method}();
						}";
                    eval($run);
                    return;
                }
            }
        }

        //Loading the 404 page
        header($_SERVER['SERVER_PROTOCOL'] . " 404 Not Found");
        $siteController = new SiteController();
        $siteController->page404();
    }

    private function getRequest()
    {
        $check = new Check();
        if ($check->exists($_GET)) {
            $get = $check->secureVar($_GET);
            if ($check->exists($get['r'])) {
                $r = $get['r'];
                $r = mb_split('/', $r);
                if ($check->exists($r[0])) {
                    if ($check->exists($r[1])) {
                        return ['controller' => $r[0], 'method' => $r[1]];
                    }
                }
            }
        }
        return false;
    }

    private function findController($controller)
    {
        $controller = ucfirst($controller);
        $controller .= 'Controller';

        $s = DIRECTORY_SEPARATOR;
        if ($this->findFile(__DIR__ . "{$s}..{$s}controllers", "{$controller}.php")) {
            if (class_exists($controller)) {
                return $controller;
            }
        }
        return false;
    }

    private function findMethod($controller, $method)
    {
        return method_exists($controller, $method);
    }

    private function findFile($path, $name)
    {
        $name = mb_strtolower($name);
        $mass = scandir($path);
        foreach ($mass as $v) {
            $v = mb_strtolower($v);
            if ($v == $name) {
                return true;
            }
        }
        return false;
    }
}