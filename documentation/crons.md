Cron Jobs
===
[Back: Sphinx Configuration](sphinx.md)

**Note: Bash scripts are used in crons setup to prevent from running process more than one time.**

You can add the crons using crontab

	$ crontab -e

It runs every minute and process limited csv record export those are pending.

        * * * * * /bin/sh /home/avccqa/avcc/crons/queue_jobs.sh  > /dev/null 2>&1

Note: Above cron is using JMSJobQueueBundle to manage all import/export requests. 
Visit http://jmsyst.com/bundles/JMSJobQueueBundle/master/installation for more detail about bundle.

It runs daily and give backup of records to user.

        @daily /bin/sh /home/avccqa/avcc/crons/backups.sh  > /dev/null 2>&1

[Next: Libraries](libraries.md)