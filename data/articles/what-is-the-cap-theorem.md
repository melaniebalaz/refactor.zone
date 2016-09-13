Title:      What is the CAP theorem?
Author:     janoszen
Published:  2016-08-22
Categories: scaling
Excerpt:    When building larger computer systems, the database soon becomes a bottleneck. Of course we can scale it,
            but scalability comes at a cost. The CAP theorem describes how data storage systems behave when run on 
            multiple computers.
Social:     /images/what-is-the-cap-theorem/social.jpg

When building larger computer systems, the database soon becomes a bottleneck. Scaling out to more than one machine 
is an option, but scaling comes at a cost. And that's where the CAP theorem comes into play: it describes 
how data storage systems behave when run on multiple computers.

Let's assume you have a web platform that uses some form of traditional database. This could be MySQL, PostgreSQL, 
you name it. You have your daily backups configured, everything is running smooth. However, since your platform is 
successful, your server gets more and more load. You hire a consultant to optimize it, you move the SQL server to a 
separate machine, but at some point you can't help but notice that you need to scale out your database to more than 
one server.

Being the cautious person you are, you hopefully start to read up on this topic. After all, if you have two servers, 
which one is the source of truth? What if you update the same information on both servers at the same time? Granted, 
these don't seem like big issues at first, but if you don't pay attention to them, they will come back and haunt you.

## The CAP theorem

With that in mind, please let me introduce today's star of the show: the CAP theorem. As Eric Brewer states in his 
1999 keynote, computer systems can be described with three fundamental properties: consistency, availability and 
partition tolerance. However, any one system can only satisfy two out of three of these properties.

### Consistency

Imagine you are writing data to a database, and you have just finished sending a larger batch of records, but are 
still waiting for the results of your insert. Suddenly the server you were writing to crashes and you are left 
wondering what happened to your data?

“Classic” SQL databases running on one machine usually put great emphasis on making sure your data is either written 
fully, or is not written at all. They also ensure that whomever reads the data in the database, they always get the 
latest state, no old data is returned.

However, that is not necessarily the case when talking about a distributed database setup. Data takes time to 
propagate from one server to the others, and these database engines may not have the facilities to ensure that your 
data is always written in full.

### Availability

This is pretty straight forward. Availability means that you can read and write data to and from any node in your 
cluster, as long as the node you are connected to isn't the one failing.

However, having availability does not mean that the data you are reading is actually up to date, or if the data you 
are writing won't get lost in a subsequent crash. It just says that you can talk to a server and it will talk back to
you.

### Partition tolerance 

Let's face it, networks aren't perfect. Partition tolerance basically means that your database won't blow up if the 
your cluster is split in two parts.

### The catch

Easy, right? Well, there's a catch: if you want to run your database on more than one server, **partition tolerance is 
not optional**. Why? Because if there is a network outage between your two (or more) nodes, your database blows up, 
and that would defeat the purpose.

In other words, you're stuck with *having to chose* between consistency and availability.

<!--
## Sources

* [CAP Twelve Years Later: How the "Rules" Have Changed](https://www.infoq.com/articles/cap-twelve-years-later-how-the-rules-have-changed)
-->