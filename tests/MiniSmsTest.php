<?php

namespace Nilnice\MiniSms\Test;

use Illuminate\Support\Collection;
use Nilnice\MiniSms\Message;
use Nilnice\MiniSms\MiniSms;
use Nilnice\MiniSms\Smser;
use Nilnice\MiniSms\SmsInterface;
use PHPUnit\Framework\TestCase;

class MiniSmsTest extends TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Sms provider [Nilnice\MiniSms\Sms\ASms] not
     *                           exists.
     */
    public function testSms()
    {
        $miniSms = new MiniSms([]);
        $actual = $miniSms->getSmsProvider('a');
        self::assertInstanceOf(SmsInterface::class, $actual);
    }

    public function testSend()
    {
        $to = random_int(000, 999);
        $message = ['content' => 'Hello World'];
        $collection = new Collection($message);
        $mock = $this->getMockBuilder(MiniSms::class)
                     ->disableOriginalConstructor()
                     ->setMethods(['send'])
                     ->getMock();
        $mock->expects($this->any())
             ->method('send')
             ->will($this->returnValue($collection));

        $actual = $mock->send($to, $message);
        self::assertSame('Hello World', $actual->get('content'));
    }

    public function testGetConfig()
    {
        $config = ['foo' => 'bar'];
        $miniSms = new MiniSms($config);
        $actual = $miniSms->getConfig();
        self::assertEquals($config, $actual->all());
    }

    public function testGetSmser()
    {
        $miniSms = new MiniSms([]);
        $actual = $miniSms->getSmser();
        self::assertInstanceOf(Smser::class, $actual);
    }
}
