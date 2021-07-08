<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\NewsletterNotification;
use Illuminate\Console\Command;

class SendNewsletterCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:newsletter
                                {emails?*}
                                 {--s | schedule : Si debe ser ejecutado directamente o no}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviá un correo electrónico';

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
     * @return int
     */
    public function handle()
    {
        $userEmails = $this->argument('emails');
        $schedule = $this->option('schedule');
        $builder = User::query();

        if($userEmails){
            $builder->whereIn('email',$userEmails);
        }

        $builder->whereNotNull('email_verified_at');
        $count = $builder->count();


        if($count){

            $this->info("Se enviaran {$count} correos");

            if($this->confirm('¿Esta de acuerdo?') || $schedule){

                $this->output->progressStart($count);

                $builder->each(function (User $user){
                    $user->notify(new NewsletterNotification());
                    $this->output->progressAdvance();
                    });

                $this->output->progressFinish();

                $this->info(" | Se enviaron {$count} correos");

                return;
            }

        }

        $this->info('No se envió ningún creo');


    }
}
