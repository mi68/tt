<?php


namespace DesignPattern\Behavioral\Observer;


use PHPUnit\Framework\TestCase;

class Test extends TestCase
{
    protected $observer;

    protected function setUp()
    {
        $this->observer = new UserObserver();
    }

    /**
     * 测试通知
     */
    public function testNotify()
    {
        //$this->expectOutputString('DesignPatterns\Behavioral\Observer\User has been updated');
        $subject = new User();

        $subject->attach($this->observer);
        $subject->property = 123;
        $subject->notify();
    }

    /**
     * 测试订阅
     */
    public function testAttachDetach()
    {
        $subject = new User();
        $reflection = new \ReflectionProperty($subject, 'observers');

        $reflection->setAccessible(true);
        /** @var \SplObjectStorage $observers */
        $observers = $reflection->getValue($subject);

        $this->assertInstanceOf('SplObjectStorage', $observers);
        $this->assertFalse($observers->contains($this->observer));

        $subject->attach($this->observer);
        $this->assertTrue($observers->contains($this->observer));

        $subject->detach($this->observer);
        $this->assertFalse($observers->contains($this->observer));
    }

    /**
     * 测试 update() 调用
     */
    public function testUpdateCalling()
    {
        $subject = new User();
        $observer = $this->getMockBuilder('\SplObserver')
            ->setMethods(['update'])
            ->disableAutoload()
            ->getMock();

        $observer->expects($this->once())
            ->method('update')
            ->with($subject);


        $subject->attach($observer);

        $subject->notify();
    }
}