<?php

namespace Kraken\_Unit\Runtime\Command\_T;

use Kraken\Channel\ChannelBase;
use Kraken\Channel\ChannelBaseInterface;
use Kraken\Command\CommandInterface;
use Kraken\Core\Core;
use Kraken\Core\CoreInterface;
use Kraken\Runtime\Command\Command;
use Kraken\Runtime\Container\ThreadContainer;
use Kraken\Runtime\RuntimeInterface;
use Kraken\Runtime\RuntimeManagerInterface;
use Kraken\Supervisor\SupervisorInterface;
use Kraken\Throwable\Exception\Logic\InstantiationException;
use Kraken\Test\TUnit;
use Exception;

class TCommand extends TUnit
{
    /**
     * @var string
     */
    protected $class = '';

    /**
     * @var CommandInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $cmd;

    /**
     * @var RuntimeManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $manager;

    /**
     * @var RuntimeInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $runtime;

    /**
     * @var CoreInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected $core;

    /**
     *
     */
    public function setUp()
    {
        parent::setUp();

        $this->cmd = null;
        $this->manager = null;
        $this->runtime = null;
    }

    /**
     *
     */
    public function testApiConstructor_CreatesInstance()
    {
        $command = $this->createCommand();
        $class   = Command::class;

        $this->assertTrue($command instanceof $class);
        $this->assertInstanceOf(CommandInterface::class, $command);
    }

    /**
     *
     */
    public function testApiConstructor_SetsRuntimeInContext()
    {
        $command = $this->createCommand();
        $context = $this->getProtectedProperty($command, 'context');

        $this->assertInstanceOf(RuntimeInterface::class, $context['runtime']);
    }

    /**
     *
     */
    public function testApiConstructor_ThrowsException_WhenNoRuntimeIsPassed()
    {
        $this->setExpectedException(InstantiationException::class);
        $command = $this->createCommand([ 'runtime' => null ]);
    }

    /**
     *
     */
    public function testApiDestructor_DoesNotThrowException()
    {
        $command = $this->createCommand();
        unset($command);
    }

    /**
     * @param string[]|null $methods
     * @return ChannelBaseInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createChannel($methods = [])
    {
        $channel = $this->getMock(ChannelBase::class, $methods, [], '', false);

        if ($this->cmd !== null && $this->existsProtectedProperty($this->cmd, 'channel'))
        {
            $this->setProtectedProperty($this->cmd, 'channel', $channel);
        }

        return $channel;
    }

    /**
     * @param string[]|null $methods
     * @return SupervisorInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createSupervisor($methods = [])
    {
        $super = $this->getMock(SupervisorInterface::class, $methods, [], '', false);

        if ($this->cmd !== null && $this->existsProtectedProperty($this->cmd, 'supervisor'))
        {
            $this->setProtectedProperty($this->cmd, 'supervisor', $super);
        }

        return $super;
    }

    /**
     * @return RuntimeManagerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createManager()
    {
        if ($this->cmd !== null && $this->existsProtectedProperty($this->cmd, 'manager'))
        {
            $this->setProtectedProperty($this->cmd, 'manager', $this->manager);
        }

        return $this->manager;
    }

    /**
     * @param string[]|null $methods
     * @return RuntimeInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    public function createRuntime($methods = [])
    {
        $methods = array_merge($methods, [
            'manager',
            'getCore'
        ]);

        $manager = $this->getMock(RuntimeManagerInterface::class, [], [], '', false);
        $core    = $this->getMock(Core::class, [ 'make' ], []);
        $core
            ->expects($this->any())
            ->method('make')
            ->will($this->returnValue(null));

        $runtime = $this->getMock(ThreadContainer::class, $methods, [ 'parent', 'alias', 'name' ]);
        $runtime
            ->expects($this->any())
            ->method('manager')
            ->will($this->returnValue($manager));
        $runtime
            ->expects($this->any())
            ->method('getCore')
            ->will($this->returnValue($core));

        if ($this->cmd !== null)
        {
            $this->setProtectedProperty($this->cmd, 'runtime', $runtime);
        }

        $this->manager = $manager;
        $this->runtime = $runtime;
        $this->core    = $core;

        return $runtime;
    }

    /**
     * @param array $context
     * @param array $methods
     * @return Command|\PHPUnit_Framework_MockObject_MockObject
     * @throws Exception
     */
    public function createCommand($context = [], $methods = [])
    {
        if ($this->class === '')
        {
            throw new Exception('Class not set');
        }

        if (!array_key_exists('runtime', $context))
        {
            $context['runtime'] = $this->createRuntime();
        }

        $this->cmd = $this->getMock($this->class, $methods, [ $context ]);

        return $this->cmd;
    }
}