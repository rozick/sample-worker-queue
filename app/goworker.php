#!/usr/bin/env php

<?php
/**
 * Create our console command to run our worker.
 */
use App\Modules\Worker;
use App\Queue;

$worker = new Worker();
$queue = Queue::all();

return $queue;


