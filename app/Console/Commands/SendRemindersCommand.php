<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Notification;
use Illuminate\Notifications\Notifiable;
class SendRemindersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reminders:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //ligne hedi maaneha 9bal bi 4 ayam men start_time
         $users = \App\User::whereBetween('start_time', [now(), now()->addDays(4)])->get();
         
            $users->each(function ($ticket) {
               // \Notification::send($ticket->user, new  \App\Notifications\AppNotify($ticket));

                 $ticket->notify(new \App\Notifications\AppNotify($ticket));
            });
    
    
    }
}
