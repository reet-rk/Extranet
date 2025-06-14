<?php

namespace BookStack\Console\Commands;

use BookStack\Entities\Models\PageRevision;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use BookStack\Entities\Queries\EntityQueries;
use BookStack\Activity\ActivityQueries;
use BookStack\Users\UserRepo;
use BookStack\Activity\Notifications\Messages\EmailUpdateNotification;
use BookStack\Users\Models\User;

class SendUpdateEmailCommand extends Command
{
    public function __construct(
        protected ActivityQueries $activityQueries,
        protected EntityQueries $queries,
        protected UserRepo $userRepo
    ) {
        parent::__construct();
    }
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bookstack:email-updates
                            {--hours= : Email updates done in last X hours}
                            {--batch= : Batch number for emails}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Email Updates to all users';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Get all the latest activity
        $details = $this->snakeCaseOptions();

        if (empty($details['hours'])) {
            Log::info('hours parameter required in command');
            return 1;
        }

        if (empty($details['batch'])) {
            Log::info('batch parameter required in command');
            return 1;
        }

        $duration = $details['hours'];
        $batchNumber = $details['batch'];
        
        $currentDate = new \DateTime();
        
        //send weekend updates on monday
        
        if ($currentDate->format( 'N' ) == 1){
                $duration = 72;
        }
        
        

        $date = (new \DateTime())->modify('-'.$duration.' hours');
        $skipUser = $this->userRepo->getByEmail('admin@admin.com');

        //$activity = $this->activityQueries->latest(5);
        $recentlyUpdatedPages = $this->queries->pages->start()
            //->visibleForList()
            ->where('draft', false)
            ->where('updated_at',">",$date)
            ->where('updated_by',"!=",$skipUser->id)
            ->orderBy('updated_at', 'desc')
            //->take(5)
            ->get();

        $userList = User::query()
            ->where('email', '!=', 'admin@admin.com')
            ->where('email', '!=', 'guest@example.com')
            ->skip(($batchNumber-1) * 10)
            ->orderBy('id','asc')
            ->take(10)
            ->get();

        Log::info('Batch '.$batchNumber.' Sending updates to');
        Log::info($userList);
            
        if($recentlyUpdatedPages->count() == 0){
            //Log::info($recentlyUpdatedPages);
            Log::info('No Updates for today.');
            return 0;     
        }

        Log::info('Batch '.$batchNumber.' Sending updates to');
        foreach($userList as $emailUser){
          // $emailUser->notify(new EmailUpdateNotification($recentlyUpdatedPages, $skipUser));
          Log::info($emailUser);
        }

        //Get users
        //$user = $this->userRepo->getByEmail('taylineo@gmail.com');
       // $user1 = $this->userRepo->getByEmail('rrkanwale@gmail.com');

        //Email code
        //foreach($userList as $emailUser) {
           //  $user->notify(new EmailUpdateNotification($recentlyUpdatedPages, $skipUser));
        //  $user1->notify(new EmailUpdateNotification($recentlyUpdatedPages, $skipUser));
          //   sleep(5);
       // }

        return 0;
    }


    protected function snakeCaseOptions(): array
    {
        $returnOpts = [];
        foreach ($this->options() as $key => $value) {
            $returnOpts[str_replace('-', '_', $key)] = $value;
        }

        return $returnOpts;
    }
}