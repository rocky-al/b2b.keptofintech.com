<?php

/**
 * This code was generated by
 * \ / _    _  _|   _  _
 * | (_)\/(_)(_|\/| |(/_  v1.0.0
 * /       /
 */

namespace Twilio\Rest\Video\V1\Room\Participant;

use Twilio\ListResource;
use Twilio\Version;

class AnonymizeList extends ListResource {
    /**
     * Construct the AnonymizeList
     *
     * @param Version $version Version that contains the resource
     * @param string $roomSid The SID of the participant's room
     * @param string $sid The unique string that identifies the resource
     */
    public function __construct(Version $version, string $roomSid, string $sid) {
        parent::__construct($version);

        // Path Solution
        $this->solution = ['roomSid' => $roomSid, 'sid' => $sid, ];
    }

    /**
     * Constructs a AnonymizeContext
     */
    public function getContext(): AnonymizeContext {
        return new AnonymizeContext($this->version, $this->solution['roomSid'], $this->solution['sid']);
    }

    /**
     * Provide a friendly representation
     *
     * @return string Machine friendly representation
     */
    public function __toString(): string {
        return '[Twilio.Video.V1.AnonymizeList]';
    }
}