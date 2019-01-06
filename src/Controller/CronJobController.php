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
     * @throws \App\Exception\CronTabsException
     * @throws \Exception
     */
    public function post(Request $request, Response $response, array $args)
    {
        $body = $request->getParsedBody();
        $expression = $body['expression'];
        $crontabName = $args['name'];
        $cronjobName = $body['name'];
        $previousName = $body['previous_name'];
        $command = $body['command'];
        $filename = CRONTABS_PATH . '/crontab.' . $crontabName . '.txt';
        $activated = false;
        if (array_key_exists('activated', $body) && $body['activated'] === "on") {
            $activated = true;
        }

        $redirectGoodUrl = '/cronwa/';
        $redirectEditUrl = "/cronwa/edit/" . $crontabName . "/" . $previousName;
        $redirectStatus = 302;


        if ($this->checkForErrors($request)) {
            return $response->withRedirect(
                $redirectEditUrl,
                $redirectStatus
            );
        }

        try {
            if (!CronJob::validate($expression)) {
                return $this->addCronexpressionError($expression, $response, $redirectEditUrl);
            }
        } catch (CronJobException $e) {
            return $this->addCronexpressionError($expression, $response, $redirectEditUrl);
        }

        /** @var CronTab $crontab */
        $crontab = $this->crontabs->getCronTab($crontabName);
        $crontab
            ->getJob($previousName)
            ->setName($cronjobName)
            ->parse($expression . ' ' . $command)
            ->setActivated($activated);

        $crontab->saveToFile($filename);

        $this->flash->addMessage('flash', 'Fichier enregistré !');

        return $response->withRedirect($redirectGoodUrl, $redirectStatus);
    }

    /**
     * @param string $expression
     * @param Response $response
     * @param string $urlRedirect
     * @return mixed
     */
    public function addCronexpressionError($expression, $response, $urlRedirect)
    {
        $this->addFlashError();
        $this->flash->addMessage('errors', $expression . " n'est pas une expression Cron valide !");

        return $response->withRedirect(
            $urlRedirect,
            302
        );
    }

    /**
     * @return $this
     */
    public function addFlashError()
    {
        $this->flash->addMessage('flash', "Il y a des erreurs dans le formulaire !");

        return $this;
    }

    /**
     * @param array $errors
     */
    public function addFormErrors($errors)
    {
        foreach ($errors as $error) {
            foreach ($error as $message) {
                $this->flash->addMessage('errors', $message);
            }
        }
    }

    /**
     * @param Request $request
     * @return bool|mixed
     */
    public function checkForErrors(Request $request)
    {
        if (!$request->getAttribute('has_errors')) {
            return false;
        }

        $errors = $request->getAttribute('errors');
        $this->addFormErrors($errors);
        $this->addFlashError();

        return true;
    }

}
