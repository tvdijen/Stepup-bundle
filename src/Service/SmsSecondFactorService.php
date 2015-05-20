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

namespace Surfnet\StepupBundle\Service;

use Surfnet\StepupBundle\Command\SendSmsChallengeCommand;
use Surfnet\StepupBundle\Command\SendSmsCommand;
use Surfnet\StepupBundle\Command\VerifyPhoneNumberCommand;
use Surfnet\StepupBundle\Exception\InvalidArgumentException;
use Surfnet\StepupBundle\Service\SmsSecondFactor\OtpVerification;
use Surfnet\StepupBundle\Service\SmsSecondFactor\SmsVerificationStateHandler;
use Symfony\Component\Translation\TranslatorInterface;

class SmsSecondFactorService
{
    /**
     * @var \Surfnet\StepupBundle\Service\SmsService
     */
    private $smsService;

    /**
     * @var \Surfnet\StepupBundle\Service\SmsSecondFactor\SmsVerificationStateHandler
     */
    private $smsVerificationStateHandler;

    /**
     * @var \Symfony\Component\Translation\TranslatorInterface
     */
    private $translator;

    /**
     * @var string
     */
    private $originator;

    /**
     * @param SmsService $smsService
     * @param SmsVerificationStateHandler $smsVerificationStateHandler
     * @param TranslatorInterface $translator
     * @param string $originator
     */
    public function __construct(
        SmsService $smsService,
        SmsVerificationStateHandler $smsVerificationStateHandler,
        TranslatorInterface $translator,
        $originator
    ) {
        if (!is_string($originator)) {
            throw InvalidArgumentException::invalidType('string', 'originator', $originator);
        }

        if (!preg_match('~^[a-z0-9]{1,11}$~i', $originator)) {
            throw new InvalidArgumentException(
                'Invalid SMS originator given: may only contain alphanumerical characters.'
            );
        }

        $this->smsService = $smsService;
        $this->smsVerificationStateHandler = $smsVerificationStateHandler;
        $this->translator = $translator;
        $this->originator = $originator;
    }

    /**
     * @return int
     */
    public function getOtpRequestsRemainingCount()
    {
        return $this->smsVerificationStateHandler->getOtpRequestsRemainingCount();
    }

    /**
     * @return int
     */
    public function getMaximumOtpRequestsCount()
    {
        return $this->smsVerificationStateHandler->getMaximumOtpRequestsCount();
    }

    public function clearSmsVerificationState()
    {
        $this->smsVerificationStateHandler->clearState();
    }

    /**
     * @param SendSmsChallengeCommand $command
     * @return bool
     */
    public function sendChallenge(SendSmsChallengeCommand $command)
    {
        $challenge = $this->smsVerificationStateHandler->requestNewOtp($command->phoneNumber);

        $body = $this->translator->trans('stepup.sms.challenge_body', ['%challenge%' => $challenge]);

        $smsCommand = new SendSmsCommand();
        $smsCommand->recipient = $command->phoneNumber->toMSISDN();
        $smsCommand->originator = $this->originator;
        $smsCommand->body = $body;
        $smsCommand->identity = $command->identity;
        $smsCommand->institution = $command->institution;

        return $this->smsService->sendSms($smsCommand);
    }

    /**
     * @param VerifyPhoneNumberCommand $command
     * @return OtpVerification
     */
    public function verifyPossession(VerifyPhoneNumberCommand $command)
    {
        return $this->smsVerificationStateHandler->verify($command->challenge);
    }
}
