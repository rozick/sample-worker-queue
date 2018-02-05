<?php

namespace App\Console\Commands;

use App\Queue;
use App\Modules\QueueClass;
use App\Modules\Worker;
use Illuminate\Console\Command;

class CreateWorkers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rafi:workers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create workers for our queue';

    /**
     * The worker process.
     *
     * @var Worker
     */
    protected $worker;
    protected $queueClass;

    /**
     * Create a new command instance.
     *
     * @param  Worker $worker
     * @params QueueClass $queueClass
     * @return void
     */
    public function __construct(Worker $worker, QueueClass $queueClass)
    {
        parent::__construct();

        $this->worker = $worker;
        $this->queueClass = $queueClass;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $worker = $this->worker;
        $queueClass = $this->queueClass;
        $queues = Queue::all();
        $worker->daemon(function () use ($worker, $queues, $queueClass) {
           echo 'Queue running... ';
           foreach ($queues as $queue) {
               if ($queue) {
                   $queueClass->applyItem($queue);
               }
               else { 
                   // do nothing
                }
           }
    });
}
    }