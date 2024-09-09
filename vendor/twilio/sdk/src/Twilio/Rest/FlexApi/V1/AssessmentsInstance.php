<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\FlexApi\V1;

use Twilio\Exceptions\TwilioException;
use Twilio\InstanceResource;
use Twilio\Options;
use Twilio\Values;
use Twilio\Version;

/**
 * PLEASE NOTE that this class contains preview products that are subject to change. Use them with caution. If you currently do not have developer preview access, please contact help@twilio.com.
 *
 * @property string $accountSid
 * @property string $assessmentId
 * @property string $offset
 * @property bool $report
 * @property string $weight
 * @property string $agentId
 * @property string $segmentId
 * @property string $userName
 * @property string $userEmail
 * @property string $answerText
 * @property string $answerId
 * @property array $assessment
 * @property string $timestamp
 * @property string $url
 */
class AssessmentsInstance extends InstanceResource {
    /**
     * Initialize the AssessmentsInstance
     *
     * @param Version $version Version that contains the resource
     * @param mixed[] $payload The response payload
     * @param string $assessmentId Assessment Id
     */
    public function __construct(Version $version, array $payload, string $assessmentId = null) {
        parent::__construct($version);

        // Marshaled Properties
        $this->properties = [
            'accountSid' => Values::array_get($payload, 'account_sid'),
            'assessmentId' => Values::array_get($payload, 'assessment_id'),
            'offset' => Values::array_get($payload, 'offset'),
            'report' => Values::array_get($payload, 'report'),
            'weight' => Values::array_get($payload, 'weight'),
            'agentId' => Values::array_get($payload, 'agent_id'),
            'segmentId' => Values::array_get($payload, 'segment_id'),
            'userName' => Values::array_get($payload, 'user_name'),
            'userEmail' => Values::array_get($payload, 'user_email'),
            'answerText' => Values::array_get($payload, 'answer_text'),
            'answerId' => Values::array_get($payload, 'answer_id'),
            'assessment' => Values::array_get($payload, 'assessment'),
            'timestamp' => Values::array_get($payload, 'timestamp'),
            'url' => Values::array_get($payload, 'url'),
        ];

        $this->solution = ['assessmentId' => $assessmentId ?: $this->properties['assessmentId'], ];
    }

    /**
     * Generate an instance context for the instance, the context is capable of
     * performing various actions.  All instance actions are proxied to the context
     *
     * @return AssessmentsContext Context for this AssessmentsInstance
     */
    protected function proxy(): AssessmentsContext {
        if (!$this->context) {
            $this->context = new AssessmentsContext($this->version, $this->solution['assessmentId']);
        }

        return $this->context;
    }

    /**
     * Update the AssessmentsInstance
     *
     * @param string $offset offset
     * @param string $answerText Answer text
     * @param string $answerId Answer Id
     * @param array|Options $options Optional Arguments
     * @return AssessmentsInstance Updated AssessmentsInstance
     * @throws TwilioException When an HTTP error occurs.
     */
    public function update(string $offset, string $answerText, string $answerId, array $options = []): AssessmentsInstance {
        return $this->proxy()->update($offset, $answerText, $answerId, $options);
    }

    /**
     * Magic getter to access properties
     *
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get(string $name) {
        if (\array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }

        if (\property_exists($this, '_' . $name)) {
            $method = 'get' . \ucfirst($name);
            return $this->$method();
        }

        throw new TwilioException('Unknown property: ' . $name);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        $context = [];
        foreach ($this->solution as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Twilio.FlexApi.V1.AssessmentsInstance ' . \implode(' ', $context) . ']';
    }
}