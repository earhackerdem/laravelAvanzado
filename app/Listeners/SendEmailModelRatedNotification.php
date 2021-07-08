<?php

namespace App\Listeners;

use App\Events\ModelRated;
use App\Models\Product;
use App\Notifications\ModelRatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendEmailModelRatedNotification
{

    public function __construct()
    {
        //
    }


    public function handle(ModelRated $event)
    {
        $rateable = $event->getRateable();

        if($rateable instanceof Product){
            $notification = new ModelRatedNotification(
                $event->getQualifier()->name,
                $rateable->name,
                $event->getScore()
            );

            $rateable->createdBy->notify($notification);
        }
    }
}
