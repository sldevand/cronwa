<?php

namespace App\Controller;

use App\Cron\CronJob;
use App\Cron\CronTab;
use App\Cron\CronTabs;
use App\Exception\CronJobException;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class CronTabsController
 * @package App\Controller
 */
class CronTabsController extends AbstractController
{
    /**
     * @var CronTabs $crontabs
     */
    protected $crontabs;

    public function __construct(ContainerInterface $c)
    {
        parent::__construct($c);
        $this->crontabs = $c->get('crontabs');
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function home(Request $request, Response $response, array $args)
    {
        $messages = $this->c->get('flash')->getMessage('flash');
        return $this->view->render(
            $response,
            'crontabs.html.twig',
            [
                'crontabs' => $this->crontabs->getCronTabs(),
                'messages' => $messages
            ]
        );
    }
}
