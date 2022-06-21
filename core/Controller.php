<?php

namespace Core;

class Controller
{
    protected $viewPath;
    protected $template;

    /**
     * @param string $view
     * @param array $variables
     * @return void
     */
    protected function render($view, $variables = [])
    {
        ob_start();
        extract($variables);

        require ($this->viewPath . str_replace('.', '/', $view).'.phtml');
        $content = ob_get_clean();

        require ($this->viewPath . 'templates/' . $this->template . '.phtml');
    }


    /**
     * @return bool
     */
    public function isAjax(){
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }

    public function Forbidden ()
    {
        header('HTTP/1.0 403 Forbidden');
        $this->render('403');
        die();
    }
    public function notFound()
    {
        header('HTTP/1.0 404 Not Found');
        $this->render('404');
        die();
    }

}