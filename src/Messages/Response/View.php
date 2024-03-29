<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Response;

use FluencePrototype\Filesystem\DirectoryNotFoundException;
use FluencePrototype\Filesystem\Filesystem;
use FluencePrototype\Filesystem\InvalidDirectoryPathException;
use FluencePrototype\Http\Messages\iResponse;
use FluencePrototype\Http\ViewData;
use FluencePrototype\Http\ViewListenerInterface;

/**
 * Class View
 * @package FluencePrototype\Http\Messages\Response
 */
class View implements iResponse, iView
{

    private array $data = [];

    /** @var ViewListenerInterface[] */
    private static array $viewListeners = [];

    /**
     * View constructor.
     * @param object $controller
     * @param string $layout
     * @param string $template
     * @param int $responseCode
     */
    public function __construct(
        private object $controller,
        private string $layout,
        private string $template,
        private int    $responseCode = StatusCodes::OK
    )
    {
    }

    public static function addViewListener(ViewListenerInterface $viewListener): void
    {
        View::$viewListeners[] = $viewListener;
    }

    /**
     * @inheritDoc
     */
    public function addData(string $key, array|bool|float|int|object|string|null $value): iView
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function render(): void
    {
        try {
            $controller = $this->controller;
            $controllerNameArray = explode('\\', get_class($controller));
            $subdomain = $controllerNameArray[2];
            $filesystem = (new Filesystem())->cd('src/App/Views');
            $viewData = new ViewData($this->data);

            foreach (View::$viewListeners as $viewListener) {
                $viewListener->listen($viewData);
            }

            ob_start();

            include $this->template;
            echo PHP_EOL;

            $template = ob_get_clean();

            http_response_code(response_code: $this->responseCode);

            include $filesystem->getDirectoryPath() . DIRECTORY_SEPARATOR . $this->layout . '.phtml';
            exit;
        } catch (DirectoryNotFoundException|InvalidDirectoryPathException $e) {
        }
    }

}