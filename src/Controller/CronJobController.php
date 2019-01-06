<?php

namespace App\Controller;

use App\Cron\CronTab;
use App\Cron\CronTabs;
use Psr\Container\ContainerInterface;
use Slim\Http\Request;
use Slim\Http\Response;

/**
 * Class CronJobController
 * @package App\Controller
 */
class CronJobController extends AbstractController
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

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     * @throws \App\Exception\CronTabsException
     * @throws \Exception
     */
    public function edit(Request $request, Response $response, array $args)
    {
        $tab = $args['tab'];
        $job = $args['job'];

        $messages = $this->flash->getMessage('flash');
        $errors = $this->flash->getMessage('errors');

        /** @var CronTab $cronTab */
        $cronTab = $this->crontabs->getCronTab($tab);
        $job = $cronTab->getJob($job);

        return $this->view->render(
            $response,
            'crontab-form.html.twig',
            [
                'cronTab' => $cronTab,
                'job' => $job,
                'messages' => $messages,
                'errors' => $errors
            ]
        );
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return mixed
     */
    public function post(Request $request, Response $response, array $args)
    {
        $body = $request->getParsedBody();

        if (!$request->getAttribute('has_errors')) {
            $this->flash->addMessage('flash', 'Fichier enregistré !');

            //TODO persistence in file
            return $response->withRedirect('/cronwa/', 302);
        }


        $errors = $request->getAttribute('errors');

        $this->addFormErrors($errors);
        $this->flash->addMessage('flash', "Il y a des erreurs dans le formulaire !");

        return $response->withRedirect(
            "/cronwa/edit/" . $args['name'] . "/" . $body['previous_name'],
            302
        );
    }

    /**
     * @param array $errors
     */
    public
    function addFormErrors($errors)
    {
        foreach ($errors as $error) {
            foreach ($error as $message) {
                $this->flash->addMessage('errors', $message);
            }
        }
    }

}
