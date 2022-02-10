# Airtable reminders

## `DailyTimesheetReminderCommand`
Using [sleiman/airtable-php](https://github.com/sleiman/airtable-php) we are retrieving the tasks for each team member and if the sum of the hours is less than 5 hours send `new DailyTimesheetReminder` 

### `config/airtable.php`
```php
<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Airtable Key
    |--------------------------------------------------------------------------
    |
    | This value can be found in your Airtable account page:
    | https://airtable.com/account
    |
     */
    'key' => env('AIRTABLE_KEY'),

    /*
    |--------------------------------------------------------------------------
    | Airtable Base
    |--------------------------------------------------------------------------
    |
    | This value can be found once you click on your Base on the API page:
    | https://airtable.com/api
    | https://airtable.com/[BASE_ID]/api/docs#curl/introduction
    |
     */
    'base' => env('AIRTABLE_BASE'),

    /*
    |--------------------------------------------------------------------------
    | Default Airtable Table
    |--------------------------------------------------------------------------
    |
    | This value can be found on the API docs page:
    | https://airtable.com/[BASE_ID]/api/docs#curl/table:tasks
    | The value will be hilighted at the beginning of each table section.
    | Example:
    | Each record in the `Tasks` contains the following fields
    |
     */
    'default' => 'default',

    'tables' => [
        'timesheet' => env('AIRTABLE_TABLE')
    ],
];

```

### `config/airtable-collaborators.php`
```php
<?php
return [
    /**
     * Fill this with:
     * key: Collaborator name
     * value: Notification email
     */
     
     "Team Member1" => "member1@team.com",
     "Team Member2" => "member2@team.com",
     "Team Member3" => "member3@team.com",
];
```
