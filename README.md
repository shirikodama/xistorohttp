Transistors over HTTP

This module implements a basic transistor which implements an AND function from
the base and collector to the emitter. See http://rip-van-webble.blogspot.com/2013/03/transistors-over-http.html
for longer explanation for this craziness.

To get this to work, you just need to load the php into a directory that your webserver servers,
and make certain that your web server can write that directory (the sqlite3 database will be
auto-created on your first fab request). 

Right now, fab.php is purely by a GET url -- no forms, no nothing just like the old days -- 
so in order to create/update a transisor,

http://[your-server]/[your-path]/fab.php?id=[numeric id of transistor]&ctype=transistor&output=[url]

output can be nothing, or the url of the next transistor in the network.

to change the state of your transitors, you use transistor.php:

http://[your-server]/[your-path]/transistor.php?id=[xistor id]&pin=[base|collector]&state[0|1]

Note that this is what you'd put in the output= parameter for fab.php if you want to create a
network. note also, you'll need to urlencode the output too like:

http://mtcc.com/ee/transistor.php%3Fid=2%26pin=base

Enjoy!
