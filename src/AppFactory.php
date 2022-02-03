<?php
namespace Merophp\Framework;

use Exception;

use Merophp\Framework\Autoloader\Autoloader;
use Merophp\ObjectManager\ObjectManager;
use Merophp\ObjectManager\ObjectContainer;

use Merophp\Framework\Http\Factory\ServerRequestFactory;
use Merophp\Framework\Http\RequestHandler;
use Merophp\Framework\Http\FinalRequestHandler;

class AppFactory
{

    /**
     * @api
     * @param array $options
     * @return App
     * @throws Exception
     */
    public static function create(array $options=[]): App
    {
        self::createObjectManager();

        $app = ObjectManager::get(
            App::class
        );
        $app->setRequestHandler(
            self::createRequestHandler()
        );
        $app->setRequest(
            ServerRequestFactory::fromGlobal()
        );

        //If the autoloader package is included, instantiate the framework autoloader
        if(class_exists(\Merophp\Autoloader\Autoloader::class)){
            $GLOBALS['autoloader'] = ObjectManager::get(Autoloader::class);
        }

        return $app;
    }

    /**
     * @throws Exception
     */
    private static function createObjectManager()
    {
        $oc = new ObjectContainer;
        ObjectManager::setObjectContainer($oc);
    }

    /**
     * @return RequestHandler
     */
    protected static function createRequestHandler(): RequestHandler
    {
        $finalRequestHandler = ObjectManager::get(
            FinalRequestHandler::class
        );

        return new RequestHandler($finalRequestHandler);
    }
}
