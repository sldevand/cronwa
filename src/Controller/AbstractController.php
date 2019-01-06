<?php

namespace App\Controller;

use Psr\Container\ContainerInterface;
use Slim\Flash\Messages;
use Slim\Views\Twig;

/**
 * Class AbstractController
 * @package App\Controller
 */
abstract class AbstractController
{
    /**
     * @var ContainerInterface $c
     */
    protected $c;

    /**
     * @var Twig $view
     */
    protected $view;

    /**
     * @var Messages $flash
     */
    protected $flash;

    /**
     * CronJobController constructor.
     * @param ContainerInterface $c
     */
    public function __construct(ContainerInterface $c)
    {
        $this->c = $c;
        $this->view = $c->get('twig');
        $this->flash = $c->get('flash');
    }
}
