$taskName = 'Send Bill Due Tomorrow Notifications';

Unregister-ScheduledTask -TaskName $taskName -Confirm:$false
