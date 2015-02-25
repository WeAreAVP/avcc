Cron Jobs
===
[Back: NOID Setup](noid.md)

**Note: Bash scripts are used in crons setup to prevent from running process more than one time.**

You can add the crons using crontab

	$ crontab -e

It runs every minute and process limited csv record export those are pending.

* * * * * /bin/sh /home/avccqa/avcc/crons/export_csv.sh  > /dev/null 2>&1

It runs daily and give backup of records to user.

@daily /bin/sh /home/avccqa/avcc/crons/backups.sh  > /dev/null 2>&1

[Next: Installation and Configuration](install-configure.md)