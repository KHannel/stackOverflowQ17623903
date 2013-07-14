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
     *
     * @Route("/")
     * @Route("/showForm", name="show_form")
     *
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

        /**
         * In our view we will use the path helper "{{ path('show_form') }}" to resolve this to a path (controller)
         * This string comes from our @Route annotation name attribute
         */
        $formActionRoute = 'show_form';

        /**
         * If we receive a POST request to this controller then we know that the user has completed the form
         * and now we need to show them a form with an added checkbox
         */
        if ($request->isMethod('POST')) {
             // First we call getForm() so that we can then use handleRequest()
            $completedForm = $baseForm->getForm();

            /**
             * handleRequest() will bind the form data to the $userData object using the naming convention
             * (ie handleRequest() will call setNote on the field named 'note' on the Entity that we passed to
             * createFormBuilder() and setName(), setTime(), etc.
             */
            $completedForm->handleRequest($request);

            /**
             * Use our original form and just add a checkbox for confirmation
             */
            $baseForm        = $baseForm->add('confirm', 'checkbox', array('mapped' => false));

            /**
             * Change the route we are passing to the view so that we can post to the resultsPage controller instead
             * of the showForm controller
             */
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

        /**
         * Retrieve the post parameters that were passed from the confirmation form
         */
        $postData = $request->request->get('form');

        $name = $postData['name'];
        $note = $postData['note'];
        $time = $this->buildDateTimeFromPostData($postData['time']);

        $userData->setName($name);
        $userData->setNote($note);
        $userData->setTime($time);

        /**
         * We would probably persist our entity to a database here or do some other business logic
         */

        /**
         * return our $userData object to our view so that we can output the data one last time
         */
        return array(
            'savedData' => $userData,
        );
    }

    /**
     * Utility method to build our DateTime object from the form elements
     *
     * @param $postDataArray
     *
     * @return \DateTime
     */
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
