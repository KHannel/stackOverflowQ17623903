<?php

namespace Acme\DemoBundle\Controller;

use Acme\DemoBundle\Entity\UserData;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class DemoController extends Controller
{
    /**
     * @param Request $request
     * @Route("/")
     * @Route("/showForm", name="show_form")
     * @Template("AcmeDemoBundle:Demo:showForm.html.twig")
     *
     * @return array|Response
     */
    public function showFormAction(Request $request)
    {
        $userData = new UserData();

        $baseForm = $this->createFormBuilder($userData)
            ->add('name', 'text')
            ->add('note', 'text')
            ->add('time', 'datetime');

        $formActionRoute = 'show_form';

        if ($request->isMethod('POST')) {
            $completedForm = $baseForm->getForm();
            $completedForm->handleRequest($request);

            $baseForm        = $baseForm->add('confirm', 'checkbox', array('mapped' => false));
            $formActionRoute = 'results_page';

        }

        return array(
            'form'        => $baseForm->getForm()->createView(),
            'actionRoute' => $formActionRoute,
        );
    }

    /**
     * @Route("/resultsPage", name="results_page")
     * @Template()
     */
    public function showResultsAction(Request $request)
    {
        $userData = new UserData();
        $postData = $request->request->get('form');

        $name = $postData['name'];
        $note = $postData['note'];

        $time = $this->buildDateTimeFromPostData($postData['time']);

        $userData->setName($name);
        $userData->setNote($note);
        $userData->setTime($time);

        // Probably persist $userData here

        return array(
            'savedData' => $userData,
        );
    }

    protected function buildDateTimeFromPostData($postDataArray)
    {
        $day    = $postDataArray['date']['day'];
        $month  = $postDataArray['date']['month'];
        $year   = $postDataArray['date']['year'];

        $hour   = $postDataArray['time']['hour'];
        $minute = $postDataArray['time']['minute'];

        $formattedDateTimeString = "$day-$month-$year $hour:$minute";

        return new \DateTime($formattedDateTimeString);
    }
}
