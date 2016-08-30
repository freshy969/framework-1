<?php

namespace Kraken\Runtime\Container\Manager;

use Kraken\Throwable\Exception\Runtime\RejectionException;
use Kraken\Promise\Promise;
use Kraken\Promise\PromiseInterface;
use Kraken\Runtime\Container\ProcessManagerInterface;
use Kraken\Runtime\Runtime;

class ProcessManagerNull implements ProcessManagerInterface
{
    /**
     * @override
     * @inheritDoc
     */
    public function existsProcess($alias)
    {
        return false;
    }

    /**
     * @override
     * @inheritDoc
     */
    public function createProcess($alias, $name, $flags = Runtime::CREATE_DEFAULT)
    {
        return Promise::doReject(
            new RejectionException("Process [$alias] could not be created.")
        );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function destroyProcess($alias, $flags = Runtime::DESTROY_FORCE_SOFT)
    {
        return Promise::doResolve(
            "Process [$alias] was not needed to be destroyed, because it had not existed."
        );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function startProcess($alias)
    {
        return Promise::doReject(
            new RejectionException("Runtime [$alias] could not be started.")
        );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function stopProcess($alias)
    {
        return Promise::doReject(
            new RejectionException("Runtime [$alias] could not be stopped.")
        );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function createProcesses($definitions, $flags = Runtime::CREATE_DEFAULT)
    {
        return Promise::doReject(
            new RejectionException("Processes could not be created.")
        );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function destroyProcesses($aliases, $flags = Runtime::DESTROY_FORCE_SOFT)
    {
        return Promise::doResolve(
            "Processes have been destroyed."
        );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function startProcesses($aliases)
    {
        return Promise::doReject(
            new RejectionException("Processes could not be started.")
        );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function stopProcesses($aliases)
    {
        return Promise::doReject(
            new RejectionException("Processes could not be stopped.")
        );
    }

    /**
     * @override
     * @inheritDoc
     */
    public function getProcesses()
    {
        return Promise::doResolve([]);
    }

    /**
     * @override
     * @inheritDoc
     */
    public function flushProcesses($flags = Runtime::DESTROY_KEEP)
    {
        return Promise::doResolve(
            "Processes have been flushed."
        );
    }
}
