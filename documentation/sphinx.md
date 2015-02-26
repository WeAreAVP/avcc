Sphinx
===
[Back: Installation and Configuration](install-configure.md)

**Sphinx Configuration Detail**

**Note:**

* rt_field attr only used for searching and rt_attr_string is used for displaying and sorting.
* 'id' attribute is reserved and used it insert or update value in index. 

###Sphinx Indexes

**Index name:** records

**Index type:** rt (realtime)

**Index path:** `/var/lib/sphinx/records`

###Re-index Sphinx

If you want to re-index records index run command

	$ php app/console avcc:sphinx

Visit http://sphinxsearch.com/ for more detail

[Next: Cron jobs](crons.md)