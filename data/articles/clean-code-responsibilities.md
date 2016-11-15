Title:      Managing responsibilities
Author:     janoszen
Published:  2016-11-14
Categories: basics
Series:     clean-code
Excerpt:    I heard you want to be a better coder. You want to use reusable pieces, and you want to have an easier time 
            maintaining older code. You may also want to work better in a team and ensure there are less bugs.
Social:     /images/clean-code-responsibilities/social.png
Decor:      /images/clean-code-responsibilities/decor.png
Decor2x:    /images/clean-code-responsibilities/decor-2x.png

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

But what is a responsibility? Let's take a class that, for example, deals with students of a school. This class 
will hold the data for exactly one student. Just like this:

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
change**. The previous example is clear: there are two responsibilities, storing/retrieving the data and the data 
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
that relies on your save() function? Well, you're in a tough situation, and there is no perfect solution.

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
need to push a huge change to your production environment. The proxy strategy can be used in almost all 
situations when you need to split up a class.

## Up next

Awesome! So we have our responsibilities neatly split up, every class or module has only one thing to do. But what 
happens if you need to replace a module? Replacing class names gets old pretty fast, so we need a better solution. 
But that's a topic for another day.