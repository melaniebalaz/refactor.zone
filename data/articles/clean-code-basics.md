Title:      Getting started with Clean Code
Author:     janoszen
Published:  0000-00-00
Categories: basics
Excerpt:    I heard you want to be a better coder. You want to use reusable pieces, and you want to have an easier time 
            maintaining older code. You may also want to work better in a team and ensure there are less bugs.
Social:     /images/clean-code-basics/social.png
Decor:      /images/clean-code-basics/decor.png
Decor2x:    /images/clean-code-basics/decor-2x.png

I heard you want to be a better coder. You want to use reusable pieces, and you want to have an easier time 
maintaining older code. You may also want to work better in a team and ensure there are less bugs. 

The term “clean code” was most probably coined by [Robert C. “Uncle Bob” Martin](https://cleancoders.com/), who wrote
[a book with the same title](/book/uncle-bob-clean-code). You might want to give it a read, although, I find it to be
very wordy. The book covers a few underlying principles that should help you write modular code in such a way that you
can later reuse those modules.

You might have noticed, I used the name “module” and not “class” or “object”. That's because clean code is not 
specific to object-oriented programming. You can use clean code principles with any programming paradigm you 
like, for example, classic procedural programming.

## Responsibilities

When writing your code, you have to segment it somehow. The unit of organization can be classes or 
modules, depending on your programming paradigm. The general idea is that one piece of code should only deal with **a 
single responsibility**. In other words, do one thing and do it well.

But what is a responsibility? Let's take a class, for example, that deals with students of a school. This class will 
hold the data for exactly one student. Just like this:

```java
class Student {
  private string name;
  
  public void setName(string name) {
    this.name = name;
  }
  
  public string getName() {
    return this.name;
  }
}
```

Obviously, you need to save the data somewhere, for example, in a database. The question presents itself: is it a 
good idea to implement a `save()` method that stores this student record? After all, this would be terribly convenient:

```java
Student joe = new Student();
joe.setName('Joe');
joe.save();
```

Easy, right? Well, not so fast. Imagine the following situation. You implement this class with MySQL in mind, which 
is a fairly standard database engine in the web world. One faithful day, your boss comes to you and tells you that the 
system administrators have been complaining, the servers are overloaded. After a brief hunt you discover that your 
`students` table is insanely large and slow, so you now decide to implement caching for your student data. The data is 
read from MySQL and written, for example, into Memcache.

So now your Student class needs to know about both MySQL and Memcache. By now your simple `Student` class that was 
only supposed to give you easy access to the student data has grown to a considerable size and now presents a 
maintenance problem. There's a lot of code which you can't even test. But hey, such is life, right?

The following week, Déjà Vu, your boss is at your desk again. The sysadmins are complaining again. (Can't they just 
buy more hardware? Come on.) Now it's your `courses` table that's causing problems. You decide to go the same route 
and copy over the code for Memcache to your `Courses` class.

Yes, yes, I can hear you scream that you would never do that. You would always decide to refactor your code to avoid 
duplication. But believe me, others won't. Unless you work alone, you will have to deal with people who have a higher 
tolerance for duplicated code.

So how can we avoid this situation? How can we make sure this doesn't happen, even if it isn't you editing the code? 
The answer lies in the word *responsibility*. With our `save()` method we have made a mistake. We have put more than 
one responsibility into one class. The `Student` class is responsible for both holding the student record and saving 
it to the database.

In the words of the great Uncle Bob, **a class has a single responsibility if it has one and only one reason for 
change**. The previous example is clear: there are two responsibilities, storing/retreiving the data and the data 
structure itself. These should be decoupled so we can change them independently.

So how do we fix this? Remember, we said that we wanted to have one class to have only one responsibility. So let's 
split it in two. Let's keep the original `Student` class as it is and create a second class for storing and 
retrieving the `Student` object.

```java
class MySQLStudentStorage {
  public Student getById(int id) {
    //...
  }
  
  public void store(Student Student) {
    //...
  }
}
```

Of course, this is easy when you have practically no code yet. But what do you do if you already have a ton of code 
that relies on your save() function? Well, you're in a tough situation and there is no perfect solution.

You could, for example, proxy through calls from the `Student` class like this:

```java
class Student {
  //...
  
  public void save() {
    storage = new MySQLStudentStorage();
    storage.store(this);
  }
}
```

Using this proxy solution will help you greatly because you can rewrite your code one module at a time and you don't 
need to push a gigantic change to your production environment. The proxy strategy can be used in almost all 
situations when you need to split up a class.

## Making modules replaceable

Awesome! So we have our responsibilities neatly split up, every class or module has only one thing to do. But what 
happens if you need to replace a module? You are still going to have code like this:

```java
public void createStudent() {
  storage = new MySQLStudentStorage();
  student = new Student();
  student.setName('Joe');
  storage.store(student);
}
```

As you can see, our code still relies on the StudentStorage class. Having such a direct dependency is a problem, 
because we need to change the class name in every single place. That sounds like fun! OK, maybe not.

On a serious note, why is this *tight coupling* a problem? Imagine that you are building a large application, with a 
couple modules, some of which are made by third parties. After all, you wouldn't build your own database driver, 
would you?

So you have structure like this:

```dotsvg
digraph {
  node [style=filled, fillcolor="#63bc46",shape=box,fontname="Open Sans;sans-serif",fontsize=16]
  edge [fontname="Open Sans;sans-serif",fontsize=14]

  subgraph high {
    label="High level modules";
    rank="same";
    graph [style="dotted"];
    ui [label=<<font color="white">User interface  </font>>]
  }
  subgraph medium {
    label="Medium level modules";
    rank="same";
    graph [style="dotted"];
    student [label=<<font color="white">Student business logic module  </font>>]
    teacher [label=<<font color="white">Teacher business logic module  </font>>]
  }
  subgraph low {
    label="Low level modules";
    rank="same";
    graph [style="dotted"];
    mysql [label=<<font color="white">MySQL connector  </font>>]
    image [label=<<font color="white">Filesystem profile image storage  </font>>]
    oracle [label=<<font color="white">Oracle connector  </font>>]
  }
  
  ui -> student [label="calls"];
  ui -> teacher [label="calls"];
  teacher -> oracle [label="calls"];
  teacher -> image [label="calls"];
  student -> image [label="calls"];
  student -> mysql [label="calls"];
}
```

What happens if with a software upgrade the `Filesystem profile image storage` breaks? Not upgrading is not an option, 
because you won't get security updates and bugfixes. You *could* switch to a different module that provides a similar 
functionality, but that would mean you have to rewrite all your modules that are using that low level module.

