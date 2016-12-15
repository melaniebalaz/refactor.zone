Title:      Managing dependencies
Author:     janoszen
Published:  2016-12-15
Categories: basics
Series:     clean-code
Excerpt:    Managing dependencies is hard, especially if we are using third party libraries and projects. Let's talk 
            about splitting our code into layers!
Social:     /images/clean-code-dependencies/social.png
Decor:      /images/clean-code-dependencies/decor.png
Decor2x:    /images/clean-code-dependencies/decor-2x.png

Awesome! So [after the last article](/clean-code-responsibilities), we have our responsibilities neatly split up, every 
class or module has only one thing to do. But what happens if you need to replace a module? You are still going to 
have code like this:

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

So you have a structure like this:

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

What happens if with a software upgrade the `Filesystem profile image storage` breaks? Not upgrading is not an option 
because you won't get security updates and bugfixes. You *could* switch to a different module that provides a similar 
functionality, but that would mean you have to rewrite all your modules that are using that low-level module.

Let's clarify this a little bit: you are probably never going to switch from using MySQL to using Oracle as a database. 
Those large scale database migrations are just not feasible in a lot of projects. But you could switch from one 
cloud provider to another for storing images, or you could just exchange the library you are using to access the 
same database or cloud provider.

And that's the point: everyone writes bad code. I, for one, write a lot of bad code. What I like to do is structure 
my application in such a way that *I can trash my bad code one piece at a time*. I don't want a big ball of code that
I can't remove parts of without completely rewriting large chunks of other modules.

That's where the **Dependency Inversion Principle** comes into play. It says that your code should depend on 
abstractions, not concrete implementations. In the example above, we clearly violated this principle because we depend
on the concrete implementation of the `Filesystem profile image storage`.

If we are programming in an OOP language we can use *abstract classes* and *interfaces* to achieve this separation. 
If we are programming in a language like C, we can define header files that don't contain implementations to create an 
abstraction.

Let's look at an example in an OOP language:

```java
interface ProfileImageStorageInterface {
  public String store(ProfileImage profileImage);
  
  public ProfileImage retrieve(String storageIdentifier);
}
```

Easy, right? Now we can implement this interface:

```java
class FilesystemProfileImageStorage implements ProfileImageStorageInterface {
  public String store(ProfileImage profileImage) { ... }
  
  public ProfileImage retrieve(String storageIdentifier) { ... }
}
```

As you can see, this class implements the interface which also means that it must implement all methods required
by said interface. It is important to note that it must not only comply by the *letter of the interface* but also the
*spirit*. It should provide the same behavior that is (hopefully) documented in the code doc of the interface.

Since we have an abstraction, we can now depend on the interface instead of the concrete implementation:

```java
class StudentBusinessLogicModule {
  ProfileImageStorageInterface profileImageStorage;
  
  public StudentBusinessLogicModule(ProfileImageStorageInterface profileImageStorage) {
    this.profileImageStorage = profileImageStorage;
  }
}
```

In plain English, we require anyone who wants to use the `StudentBusinessLogicModule` class; you will need to pass 
some implementation of `ProfileImageStorageInterface`, but it doesn't specify which. Our dependency graph now looks 
like this:

```dotsvg
digraph {
  edge [fontname="Open Sans;sans-serif",fontsize=14]

  subgraph high {
    node [style=filled, fillcolor="#63bc46",shape=box,fontname="Open Sans;sans-serif",fontsize=16]
    label="High level modules";
    rank="same";
    graph [style="dotted"];
    ui [label=<<font color="white">User interface  </font>>]
  }
  subgraph mediuma {
    node [style=filled, fillcolor="#4663bc",shape=box,fontname="Open Sans;sans-serif",fontsize=16]
    label="Medium level modules";
    rank="same";
    graph [style="dotted"];
    studenta [label=<<font color="white">Student business logic abstraction  </font>>]
    teachera [label=<<font color="white">Teacher business logic abstraction  </font>>]
  }
  subgraph medium {
    node [style=filled, fillcolor="#63bc46",shape=box,fontname="Open Sans;sans-serif",fontsize=16]
    label="Medium level modules";
    rank="same";
    graph [style="dotted"];
    student [label=<<font color="white">Student business logic module  </font>>]
    teacher [label=<<font color="white">Teacher business logic module  </font>>]
  }
  subgraph lowa {
    node [style=filled, fillcolor="#4663bc",shape=box,fontname="Open Sans;sans-serif",fontsize=16]
    label="Low level modules";
    rank="same";
    graph [style="dotted"];
    mysqla [label=<<font color="white">MySQL connector abstraction  </font>>]
    imagea [label=<<font color="white">Profile image storage abstraction  </font>>]
    oraclea [label=<<font color="white">Oracle connector abstraction  </font>>]
  }
  subgraph low {
    node [style=filled, fillcolor="#63bc46",shape=box,fontname="Open Sans;sans-serif",fontsize=16]
    label="Low level modules";
    rank="same";
    graph [style="dotted"];
    mysql [label=<<font color="white">MySQL connector implementation  </font>>]
    image [label=<<font color="white">Filesystem profile image storage  </font>>]
    oracle [label=<<font color="white">Oracle connector implementation  </font>>]
  }
  
  ui -> studenta [label="calls"];
  ui -> teachera [label="calls"];
  teachera -> teacher [label="implements", dir=back]
  teacher -> oraclea [label="calls"];
  teacher -> imagea [label="calls"];
  studenta -> student [label="implements", dir=back];
  student -> imagea [label="calls"];
  student -> mysqla [label="calls"];
  mysqla -> mysql [label="implements", dir=back];
  oraclea -> oracle [label="implements", dir=back];
  imagea -> image [label="implements", dir=back];
}
```

## Wiring

OK, are we done yet? Can we start coding? ... Well, not so fast. Whether you are programming in an OOP language, or you 
are using C-style headers, your compiler doesn't magically know what implementations you want to pass for each 
interface.

One of the classic ways of achieving that would be the factory pattern. In this pattern, separate classes are 
constructed for making creating an object, hence the name “factory”. For example:

```java
class UIFactory {
  TeacherFactory teacherBLFactory;
  StudentFactory studentBLFactory; 

  public UIFactory(
    TeacherBLFactory teacherBLFactory,
    StudentBLFactory studentBLFactory
  ) {
    this.teacherBLFactory = teacherBLFactory;
    this.studentBLFactory = studentBLFactory;
  }

  public UI createUI() {
    return new UI(
      teacherBLFactory.createTeacherBL(),
      studentFactory.createStudent()
    );
  }
}

class TeacherBLFactory {
  public TeacherBLFactory(
    OracleConnectionFactory oracleConnectionFactory,
    ProfileImageStorageFactory profileImageStorageFactory
  ) {
    ...
  }
}
```

... you get the idea. If it sounds painful, that's because it is. We want our tools to save us time, not create more 
work. If you are trying this, you might think “I'm not doing this” and go back to writing code like this:

```java
class UI {
  private TeacherBusinessLogic teacherBL;
  public UI() {
    this.teacherBL = new TeacherBusinessLogic();
  }
}
```

But hold on, this is tight coupling and exactly what we wanted to avoid! We don't want our modules to be glued together!

## Dependency Injection Containers

Luckily, there are devices called *Dependency Injection Containers* that solve this problem. Usually they come 
in the form of libraries such as [Auryn](https://github.com/rdlowrey/auryn), [Guice](https://github.com/google/guice),
[Dagger](https://google.github.io/dagger/) or [Python DIC](https://pypi.python.org/pypi/dic/1.2.0b1).

Using these is usually quite easy. As a first step, we define our rules; for example, which implementations to use for 
our abstractions or what the scope of our objects is.

Let's talk about that a little. Defining implementations is easy. We tell the DIC the interface and the 
substitute. In Guice defining replacements would look something like this:

```java
public class SchoolModule extends AbstractModule {
  @Override 
  protected void configure() {
    bind(ProfileImageStorageInterface.class)
      .to(FilesystemProfileImageStorage.class);
  }
}
```

We can also define a scope. If we are writing a web application for example, we would want a request object to be 
reused all over the current request, but shouldn't be shared to other requests. Scoping is not supported in all DIC 
solutions, and PHP versions usually don't include it as it doesn't make a lot of sense, whereas most Java 
implementations have scoping.

Let's move on, how do we create our UI class now? It's quite simple; we ask the DIC to make us one:

```java
Injector injector = Guice.createInjector(new SchoolModule());
UI ui = injector.getInstance(UI.class);
```

That's it! The injector will create all the dependencies for us! Wow, that was easy, wasn't it? Why the hell didn't 
we do this from the start, right?

> **Be careful!** The injector should always be separated from your core application logic! It should never be 
> injected into your business logic itself because that turns it into a
> [service locator](https://en.wikipedia.org/wiki/Service_locator_pattern), which hides dependencies and is therefore
> considered an antipattern.

> **Pro tip!** Automatic dependency injection usually involves reflection which is a rather slow process! Some DIC
> implementations work around this by caching or automatic code generation. If your application startup is slow, this 
> might be the reason. Check your DIC documentation for tuning options.

## Integrating third party modules

Dependency injection is an incredibly useful tool, and to be honest, I use it whenever I can. However, sometimes it's
just too painful. For example, let's take a third party module that requires a LOT of configuration and would be a 
real pain to integrate with our DIC implementation.

These are cases when I tend to break my own rules just a tiny bit and write a wrapper. (I like my sanity, thank you 
very much.) For example, I did it in the code of this very site when I integrated the routing library. Simplified, 
the code in question could look like this:

```java
class MyRoutingAdapter implements Router {
  private SomeRoutingLibrary router;
  public MyRoutingAdapter() {
    //lot of configuration madness here.
    this.router = new SomeRoutingLibrary(
      param1, param2, param3
    );
  }
  
  public RoutingResponse route(RoutingRequest request) {
    //do routing
  }
}
```

This class hides all the ugly parts of the routing library without having to write hundreds of lines in configuration.
Needless to say, you can split it a little more and create a factory and whatnot, but a lot of times that's just not
necessary.

## Summary

I think any good architecture cuts the application into layers. In a web application you would have one layer for 
the web stuff, one layer for your business logic and one for data storage handling. Or you could split it even more 
if you so desire. The important bit is that your layers should be divided by interfaces or other methods of abstraction
 so you can easily replace each of them without having to rewrite the other.

One such architecture is the Entity-Boundary-Interactor pattern, which was proposed by Robert C. Martin. If you're 
interested, [give it a read on ReadTheDocs](http://ebi.readthedocs.io/en/latest/). 
