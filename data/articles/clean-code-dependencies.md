Title:      Managing dependencies
Author:     janoszen
Published:  0000-00-00
Categories: basics
Series:     clean-code
Excerpt:    I heard you want to be a better coder. You want to use reusable pieces, and you want to have an easier time 
            maintaining older code. You may also want to work better in a team and ensure there are less bugs.
Social:     /images/clean-code-dependencies/social.png
Decor:      /images/clean-code-dependencies/decor.png
Decor2x:    /images/clean-code-dependencies/decor-2x.png

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

