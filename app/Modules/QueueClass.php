<?php

namespace App\Modules;

use App\Queue;
use App\Config;
use Carbon\Carbon;

/**
 * @file
 * QueueClass.
 */

/**
 * Static queue implementation.
 */
class QueueClass {

 /**
  * Allows access to the reserved item
  */
  public $reserved_item;
   
 /**
  * Indicates if item is reservable
  */
  public $canReserve;

  /**
   * The queue data. We would use this if we were going to store state in shared memory. 
   * But we're going to be using a database table instead. 
   * This applies to the protected variables as well as the construct.
   *
   * @var array
   */
  protected $queue;

  /**
   * Counter for item ids.
   *
   * @var int
   */
  protected $id_sequence;

  /**
   * Start working with a queue.
   */
  public function __construct() {
    // Nothing here
  }

  /**
   * Add a queue item and store it directly to the queue.
   */
  public function createItem($data, $message_type, $category) {
    $item = new Queue;
    $item->data = $data;
    $item->created_at = Carbon::now();
    $item->expire = Carbon::now()->addMinutes(30);
    $item->type = $message_type;
    $item->category = $category;
    $item->save();
  }

  /**
   * Retrieve the number of items in the queue.
   */
  public function numberOfItems() {
    $count = Queue::all()->count();
    return $count;  
  }

  /**
   * Reserve an item in the queue for processing for a specific time.
   */
  public function reserveItem($message_type, $category, $reserver_name) {
    
    /** 
     * Implement concurrency control according to config.
     */
    $config = Config::first();
    $config = $config ? $config : new Config();

    switch ($message_type) {
      case ($message_type == 'queue'):
        $message_type_count = Queue::where('type', 'queue')->count();
        if (($message_type_count > $config->limit_queue) && ($config->limit_queue)) {
          // If above config limit, return canReserve as false
          return $this->canReserve = false;
        }
        case ($message_type == 'other'):
        $message_type_count = Queue::where('type', 'other')->count();
        if (($message_type_count > $config->limit_other) && ($config->limit_other)) {
          return $this->canReserve = false;
        }
      }

      switch ($category) {
        case ($category == 'c1'):
          $category_count = Queue::where('category', 'c1')->count();
          if (($category_count > $config->limit_c1) && ($config->limit_c1)) {
            return $this->canReserve = false;
          }
          case ($category == 'c2'):
          $category_count = Queue::where('category', 'c2')->count();
          if (($category_count > $config->limit_c2) && ($config->limit_c2)){
            return $this->canReserve = false;
          }
        }

    /**
    * Get the reserved item
    */
    $reserved_item = Queue::when($message_type, function($query) use ($message_type) {
      return $query->where('message_type', $message_type);
    })
    ->when($category, function($query) use ($category) {
      return $query->where('category', $category);
    })
    ->orderBy('created_at', 'asc')
    ->first();
    
    if (!$reserved_item) {
      return $this->canReserve = false;
    }
    $reserved_item->reserved_at = Carbon::now();
    $reserved_item->reserver_name = $reserver_name;
    $reserved_item->save();
    $this->canReserve = true;
    $this->reserved_item = $reserved_item;
  }


  /**
  * Apply item and delete it from queue
  */
    public function applyItem($item) {
      // do something with the item
      $this->deleteItem($item->id);
    }

  /**
   * Release an item that the worker could not process, so another
   * worker can come in and process it before the timeout expires.
   */
  public function releaseItem($item) {
   // edge case
  }

  /**
   * Delete a finished item from the queue.
   */
  public function deleteItem($item_id) {
    Queue::where('id', $item_id)->delete();
  }

  /**
   * Create a queue.
   */
  public function createQueue() {
    // Nothing needed here.
  }

  /**
   * Delete a queue. Truncates entire table.
   */
  public function deleteQueue() {
    Queue::truncate();
    // $this->queue = array();
    // $this->id_sequence = 0;
  }
}