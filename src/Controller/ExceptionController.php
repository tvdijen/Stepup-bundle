<?php

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupBundle\Controller;

use Surfnet\StepupBundle\Exception\Art;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as FrameworkController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\FlattenException;

class ExceptionController extends FrameworkController
{
    public function showAction(FlattenException $exception)
    {
        $statusCode = $exception->getStatusCode();

        if ($statusCode == 404) {
            $template = 'SurfnetStepupBundle:Exception:error404.html.twig';
        } else {
            $template = 'SurfnetStepupBundle:Exception:error.html.twig';
        }

        return $this->render($template, [
            'exception' => $exception,
            'art' => Art::forException($exception),
            'statusCode' => $statusCode,
            'statusText' => isset(Response::$statusTexts[$statusCode]) ? Response::$statusTexts[$statusCode] : '',
        ]);
    }
}
