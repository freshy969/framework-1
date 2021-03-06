<?php

namespace Kraken\_Unit\Runtime\Command;

use Kraken\_Unit\Runtime\_T\TSolver;
use Kraken\Runtime\Runtime;
use Kraken\Runtime\Supervision\Runtime\RuntimeDestroySoft;
use Dazzle\Throwable\Exception\Logic\IllegalCallException;
use Exception;
use StdClass;

class RuntimeDestroySoftTest extends TSolver
{
    /**
     * @var string
     */
    protected $class = RuntimeDestroySoft::class;

    /**
     *
     */
    public function testApisolver_InvokesProperAction()
    {
        $ex = new Exception();

        $origin = 'origin';
        $hash   = 'hash';
        $result = new StdClass;

        $solver  = $this->createSolver();
        $manager = $this->createManager();
        $manager
            ->expects($this->once())
            ->method('destroyRuntime')
            ->with($origin, Runtime::DESTROY_FORCE_SOFT, [ 'hash' => $hash ])
            ->will($this->returnValue($result));

        $this->assertSame(
            $result,
            $this->callProtectedMethod(
                $solver, 'solver', [ $ex, [ 'origin' => $origin, 'hash' => $hash ] ]
            )
        );
    }

    /**
     *
     */
    public function testApisolve_ThrowsException_WhenParamOriginDoesNotExist()
    {
        $solver = $this->createSolver();

        $callable = $this->createCallableMock();
        $callable
            ->expects($this->once())
            ->method('__invoke')
            ->with($this->isInstanceOf(IllegalCallException::class));

        $solver
            ->solve(new Exception, [])
            ->then(
                null,
                $callable
            );
    }
}
