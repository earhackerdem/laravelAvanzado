<?php

namespace App\Console\Commands;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Enviá un correo electrónico a los usuarios que no hay verificado su cuenta después de haberse registrado hace una semana';

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
        $builder = User::query()
        ->whereDate('created_at',Carbon::now()->subDays(7)->format('Y-m-d'))
        ->whereNull('email_verified_at');

        if($count = $builder->count()){
            $this->info("{$count} email will be sent");
            $this->output->progressStart($count);

            $builder->each(function(User $user){
                $user->sendEmailVerificationNotification();
                $this->output->progressAdvance();
            });
            $this->output->progressFinish();
            $this->info('Se han enviado los emails');
        }else{
            $this->info('No se han enviado emails');
        }
    }
}
