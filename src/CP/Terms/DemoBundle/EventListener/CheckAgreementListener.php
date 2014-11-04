<?php

namespace CP\Terms\DemoBundle\EventListener;

use CP\Bundle\TermsBundle\Exception\TermsNotAgreedException;

use Symfony\Bundle\AsseticBundle\Controller\AsseticController;
use Symfony\Bundle\WebProfilerBundle\Controller\ProfilerController;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Security\Core\SecurityContext;
use Symfony\Component\Security\Http\HttpUtils;

class CheckAgreementListener
{
    protected $utils;
    protected $context;

    public function __construct(SecurityContext $context, HttpUtils $utils)
    {
        $this->context = $context;
        $this->utils = $utils;
    }

    public function onFilterController(FilterControllerEvent $event)
    {
        if ($this->context->getToken() &&
                $this->context->isGranted('ROLE_USER') &&
                    // Following ROLES can access page without agreement
                    !$this->context->isGranted('ROLE_ADMIN')) {

            $controller = $event->getController();

            if (!is_array($controller)) {
                return;
            }

            if ($controller[0] instanceof AsseticController ||
                $controller[0] instanceof ProfilerController) {
                return;
            }

            $request = $event->getRequest();

            if ($this->utils->checkRequestPath($request, 'cp_terms') ||
                    $this->utils->checkRequestPath($request, 'cp_terms_agree') ||
                        $this->utils->checkRequestPath($request, 'cp_terms_diff') ||
                            $request->attributes->get('_controller') == 'CPTermsBundle:Frontend:agree') {
                return;
            }

            $user = $this->context->getToken()->getUser();

            if (!$user->getProfile()->hasAgreedToLatestTerms()) {
                throw new TermsNotAgreedException('You must agree to latest terms.');
            }
        }
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        $exception = $event->getException();

        if (!($exception instanceof TermsNotAgreedException)) {
            return;
        }

        $response = $this->utils->createRedirectResponse($event->getRequest(), 'cp_terms_agree');

        $event->setResponse($response);
    }
}
