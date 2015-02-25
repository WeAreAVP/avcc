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

First you need to remove old index files. Path of files is `/var/lib/sphinx/`.

You should be in data directory.

If you want to re-index records index.
	
	$ rm -rf records.*

Restart sphinx service.

	$ sudo /etc/init.d/sphinxsearch restart

Go in project directory and insert records data.

	$ php index.php sphinx carriers_insert

Visit http://sphinxsearch.com/ for more detail

[Next: Cron jobs](crons.md)