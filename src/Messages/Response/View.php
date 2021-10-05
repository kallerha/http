<?php

declare(strict_types=1);

namespace FluencePrototype\Http\Messages\Response;

use FluencePrototype\Filesystem\DirectoryNotFoundException;
use FluencePrototype\Filesystem\Filesystem;
use FluencePrototype\Filesystem\InvalidDirectoryPathException;
use FluencePrototype\Http\Messages\iResponse;

/**
 * Class View
 * @package FluencePrototype\Http\Messages\Response
 */
class View implements iResponse, iView
{

    private array $data = [];

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
        private int $responseCode = StatusCodes::OK
    )
    {
    }

    /**
     * @inheritDoc
     */
    public function addData(string $key, array|bool|float|int|object|string $value): iView
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

            extract(array: $this->data);
            ob_start();

            include $this->template;
            echo PHP_EOL;

            $template = ob_get_clean();

            http_response_code(response_code: $this->responseCode);

            include $filesystem->getDirectoryPath() . DIRECTORY_SEPARATOR . $this->layout . '.phtml';
            exit;
        } catch (DirectoryNotFoundException | InvalidDirectoryPathException $e) {
        }
    }

}