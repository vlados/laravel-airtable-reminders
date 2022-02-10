<?php

namespace App\Console\Commands;

use App\Notifications\DailyTimesheetRemineder;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Notification;
use TANIOS\Airtable\Airtable;

class DailyTimesheetReminderCommand extends Command
{
    protected $signature = 'reminders:daily';
    private $api;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->api = new Airtable(array(
            'api_key' => config('airtable.key'),
            'base' => config('airtable.base')
        ));

    }

    public function handle()
    {
        foreach (config('airtable-collaborators') as $user=>$mail) {
            if ($this->getHours($user) < 5) {
                $this->info("${user} has not filled his timesheet");
                Notification::route('mail', $mail)
                    ->notify(new DailyTimesheetRemineder(Carbon::now()->subDay()->format('d.M.Y')));
            }
        }
    }

    function getRecordsForPerson($person): array
    {
        $params = array(
            "filterByFormula" => "AND(
                IS_AFTER(({Дата}), '" . Carbon::now()->subDays(2)->format('Y-m-d') . "'),
                IS_BEFORE(({Дата}), '" . Carbon::now()->format('Y-m-d') . "'),
                {Collaborator} = '${person}',
                {Описание на задачата} != '',
                {Време} > 0
             )",
        );

        $request = $this->api->getContent(config('airtable.tables.timesheet'), $params);

        $records = [];
        do {
            $response = $request->getResponse();
            array_push($records, ...$response['records']);
        } while ($request = $response->next());

        return $records;
    }

    private function getHours($user)
    {
        $hours = (int)collect($this->getRecordsForPerson($user))->sum('fields.Време');
        return $hours / 60 / 60;
    }
}
