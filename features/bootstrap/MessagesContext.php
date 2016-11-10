<?php

/**
 * This file is part of Slick/Users
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\MinkContext;

class MessagesContext extends MinkContext implements Context
{

    /**
     * @var \Slick\Http\Client
     */
    protected $client;

    /**
     * @var array
     */
    protected $parameters = [];

    /**
     * @var object
     */
    protected $message;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     *
     * @param array $parameters
     */
    public function __construct($parameters)
    {
        $this->parameters = $parameters;
        $this->deleteMessages();
    }

    /**
     * @Given /^I should receive an e\-mail on "([^"]*)" address$/
     *
     * @param string $address
     */
    public function iShouldReceiveAnEMailOnAddress($address)
    {
        $this->assertEmailFor($address);
    }

    /**
     * @When /^I follow the token link on the e\-mail$/
     */
    public function iFollowTheConfirmLinkOnTheEMail()
    {
        $regExp = '/(?P<url>https?:\/\/.*token.*\n)/i';
        $request = new \Slick\Http\Request(\Slick\Http\Request::METHOD_GET, '/messages/'.$this->message->id.'.plain');
        $response = $this->getClient()->send($request);
        $plain = $response->getBody()->getContents();
        preg_match($regExp, $plain, $matches);
        $url = trim($matches['url']);
        $this->visitPath($url);
    }

    protected function getClient()
    {
        if (!$this->client) {
            $this->client = new \Slick\Http\Client(
                ['base_uri' => $this->parameters['mailcatcher']]
            );
        }
        return $this->client;
    }

    protected function assertEmailFor($address)
    {
        $request = new \Slick\Http\Request(\Slick\Http\Request::METHOD_GET, '/messages');
        $response = $this->getClient()->send($request);
        $messages = json_decode($response->getBody()->getContents());
        $found = false;
        foreach ($messages as $message) {
            if (in_array("<$address>", $message->recipients)) {
                $found = true;
                $this->message = $message;
            }
        }
        if (!$found) {
            throw new \Exception("No messages found for $address address.");
        }
    }

    protected function deleteMessages()
    {
        $request = new \Slick\Http\Request(\Slick\Http\Request::METHOD_DELETE, '/messages');
        $this->getClient()->send($request);
    }
}
