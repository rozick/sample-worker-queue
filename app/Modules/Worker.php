<?php

namespace App\Modules;

class Worker
{
    /**
     * Indicates if the worker should exit.
     *
     * @var bool
     */
    public $shouldQuit = false;
    /**
     * Indicates if the worker is paused.
     *
     * @var bool
     */

    public $paused = false;
    /**
     * @var bool
     */

     /**
     * Worker constructor.
     *
     * @param Options $options
     */
    public function __construct()
    {
       // nothing here
    }
    /**
     * Listen to the given queue in a loop.
     *
     * @param callable $function
     * @return void
     */
    public function daemon($function)
    {
        while (true) {
            if (!$this->daemonShouldRun()) {
                $this->pauseWorker();
                continue;
            }
            $function();
            sleep(1);
            $this->stopIfNecessary();
        }
    }
    /**
     * Determine if the daemon should process on this iteration.
     *
     * @return bool
     */
    protected function daemonShouldRun()
    {
        return !$this->paused;
    }
    /**
     * Pause the worker for the current loop.
     *
     * @return void
     */
    protected function pauseWorker()
    {
        sleep(1);
        $this->stopIfNecessary();
    }
    /**
     * Stop the process if necessary.
     *
     * @return void
     */
    protected function stopIfNecessary()
    {
        if ($this->shouldQuit) {
            $this->kill();
        }
    }
    /**
     * Kill the process.
     *
     * @param  int $status
     * @return void
     */
    public function kill($status = 0)
    {
        if (extension_loaded('posix')) {
            posix_kill(getmypid(), SIGKILL);
        }
        exit($status);
    }
}