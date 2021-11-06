<?php

// retorna configurações da aplicação
if (!function_exists('app'))
{
	function app($key)
	{
		if(!isset($app))
			$app = include __DIR__.'/../config/app.php';

		if(isset($key))
			return array_key_exists($key, $app) ? $app[$key] : null;
	}
}

// retorna página html
if (!function_exists('view'))
{
    function view(string $view, array $values=null)
    {
        // cria variaveis dinamicamente
        if($values != null)
            foreach($values as $responseName => $responseValue)
                $$responseName = $responseValue;

        // retorna view 
        $view = str_replace('.', '/', $view);
        include '../views/'.$view.'.php';

        // limpa session de inputs
        remove_session('old_fields');

        // limpa session de alertas
        remove_session('alert-errors');
        remove_session('alert-success');
    }
}

// retorna caminho para pasta public para acessar os assets
if (!function_exists('asset'))
{
    function asset($path) 
    {
        echo app('base_url').$path;
    }
}

// retorna rota completa ao passar url
if (!function_exists('url'))
{
    function url($url) 
    {
        echo app('base_url').($url == '/' ? '' : $url);
    }
}

// retorna valores dos inputs enviados na última requisição
if (!function_exists('old'))
{
    function old($inputName)
    {
        $old_fields = get_session('old_fields');

        echo isset($old_fields[$inputName]) ? $old_fields[$inputName] : '';
    }
}

// renderiza input com método de envio da rota
if (!function_exists('method_field'))
{
    function method_field($method) 
    {
        echo isset($method) ? "<input type='hidden' name='_method' value='$method'>" : "";
    }
}

// retorna classe 'active' quando estiver na url atual
if (!function_exists('classActivePath'))
{
    function classActivePath($path, $activeClass, $urlParams=[], $n=0)
    {
        // pega url atual
        $url = $urlParams == null ? strtok($_SERVER['REQUEST_URI'], '?') : $_SERVER['REQUEST_URI'];
        // trata url passada por parametro
        $path = ($path != '/' ? '/' : '') . $path;
        // escreve parametro para a url
        $http_query = $urlParams != null ? '?' . http_build_query($urlParams) : '';

        if($n != 0) {
            $i = 0;
            $final_path = '';
            $explode_path = explode('/', $url);
            while ($i != $n)
            {
                $i++;
                if(isset($explode_path[$i]))
                    $final_path .= '/'.$explode_path[$i];
            }
            if($path == $final_path)
                echo " $activeClass";

        } else {
            if($path.$http_query == $url)
                echo " $activeClass";
        }
    }
}

// retorna classe 'active' nos links de paginação quando estiver na rota atual
if (!function_exists('activePage'))
{
    function activePage($path, $activeClass, $urlParams=[], $defaultPage=1)
    {
        // verifica se é a primeira página
        if($urlParams['page'] == $defaultPage) {
            // verifica se valor não foi definido ou se não é numerico
            if(!is_numeric($_GET['page']) || !isset($_GET['page']) || $_GET['page'] == 0) {
                $urlParams = [];
            }
        }

        classActivePath($path, $activeClass, $urlParams);
    }
}

// retorna componente html com valores passados por parâmetro
if (!function_exists('partial'))
{
    function partial(string $path, array $values=[])
    {
        if($values != NULL)
                foreach($values as $responseName => $responseValue)
                    $$responseName = $responseValue;
                    
        include __DIR__."/../views/$path.php";
    }
}

// retorna objeto request
if (!function_exists('request'))
{
    function request()
    {
        if(!get_session('old_fields')) {
            put_session('old_fields', $_POST);
        }
        
        return new \Core\Request;
    }
}

// retorna mensagem de erro de um input enviado
if (!function_exists('error_field'))
{
    function error_field(string $inputName) {
        return isset($_SESSION['alert-errors'][$inputName]) ? $_SESSION['alert-errors'][$inputName] : '';
    }
}

// percorre array e para execução do script
if (!function_exists('dd'))
{
    function dd($array) {
        print_r($array);
        die();
    }
}

// realiza um redirecionamento
if(!function_exists('redirect'))
{
    function redirect(string $route)
    {
        header("Location: ".(isset($_SERVER['HTTPS']) && !empty($_SERVER['HTTPS']) ? 'https' : 'http')."://".$_SERVER['HTTP_HOST'].$route);
        exit;
    }
}

// inicia session
if(!function_exists('start_session'))
{
    function start_session()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

// pega valor da session
if(!function_exists('get_session'))
{
    function get_session($key)
    {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }
}

// cria session
if(!function_exists('put_session'))
{
    function put_session($key, $value)
    {
        $_SESSION[$key] = $value;
    }
}

// altera session como array
if(!function_exists('push_session'))
{
    function push_session($key, $value)
    {
        $session = $_SESSION[$key];
        array_push($session, $value);
        $_SESSION[$key] = $session;
    }
}

// remove session
if(!function_exists('remove_session'))
{
    function remove_session($key)
    {
        if(isset($_SESSION[$key]))
            unset($_SESSION[$key]);
    }
}
    



