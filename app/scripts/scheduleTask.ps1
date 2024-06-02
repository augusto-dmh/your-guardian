$executable = 'php-executable-name'
$artisanPath = 'path-to-artisan-file'
$command = 'command-signature'
$triggerTime = '0pm'
$taskName = 'Send Bill Due Tomorrow Notifications';
$taskDescription = 'Run Laravel command to send bill due tomorrow notifications';

$action = New-ScheduledTaskAction -Execute $executable -Argument "$artisanPath $command"
$trigger = New-ScheduledTaskTrigger -Daily -At $triggerTime
Register-ScheduledTask -Action $action -Trigger $trigger -TaskName $taskName -Description $taskDescription
