Title:      Introduction to SQL databases
Subtitle:   Part 1
Author:     janoszen
Published:  2016-10-11
Categories: basics
Excerpt:    Whether you are running a webapp, a financial system or a game, you need some method of storing your data.
            SQL allows you to query most traditional databases, like MySQL or PostgreSQL. Let's take a look.
Social:     /images/sql-introduction-part1/social.png
Decor:      /images/sql-introduction-part1/decor.png
Decor2x:    /images/sql-introduction-part1/decor-2x.png

Whether you are running a webapp, a financial system or a game, you need some method of storing your data. SQL allows
you to query most traditional databases, like MySQL or PostgreSQL. Let's take a look.

SQL, short for Structured Query Language, is a method of querying traditional, relational databases (RDBMS). We'll 
take a look at the relational part in a bit, but for now it's enough to know that SQL is a text language to describe 
what you want to ask a database.

If you want to try out the code here, you will need some sort of SQL database. You can either install
[MySQL](https://www.mysql.com/), [PostgreSQL](https://www.postgresql.org/) or any other database server on your 
computer, or you can just go to [sqlfiddle.com](http://sqlfiddle.com/) and play around there.

> **Tip** Database servers usually implement their own flavor of SQL. The syntax may vary slightly, check your 
> server's documentation for details.

## Why SQL?

SQL is a very old language and it is very widely adopted. Using SQL databases was still the defacto standard of 
storing data. In fact, it still is, although several NoSQL variants have gained a foothold, mostly in web application
and cloud computing.

SQL databases, also referred to as RDBMS (relational database management system) provide an easy way of creating a 
strict data structure, and also allows for querying said data. It also provides a method of connecting related data 
together, thus creating a valuable tool for people working with complex business cases.

In general, the vast majority of computer systems use SQL databases, no matter if we are talking about an Android 
app, or a banking system, so it's worthwhile putting some effort into.

## Organizing data

When building a database, it's all about modelling your data. You want to have a data structure that gives you easy 
and fast access to the data you want. So you need to organize your data into some structure. RDBMS organize data into
*tables*. Yes, almost like Excel. Well, somewhat.

So what's the difference? Well, in Excel you can organize data vertically or horizontally, you can use cells next to 
your table for additional data, etc. RDBMS don't support that. In SQL, you have to *predefine* your columns in a 
table, and you can only insert data as rows.

Let's create our first table, shall we?

```sql
CREATE TABLE students (
    name VARCHAR(255),
    birthday DATETIME
);
```

So far, so good. We now have a table that has two columns, `name` and `birthday`. How do you now put data into it? 
Well, that's easy:

```sql
INSERT INTO students (
  name,
  birthday
) VALUES (
  'Janos Pasztor',
  '1984-08-16'
);
```

> **Tip** You don't have to specify the column names explicitly if you don't want to. This also means that you 
> have to provide data for all columns, and it is very error prone, since a change in the data structure may cause a 
> misleading error message.
> 
> Therefore it is recommended that you always specify the columns you want to work with, which has the added benefit 
> of allowing you to skip columns, leaving them to be filled with default values.

Since you presumably now have data in our database, so we expect to get something like this when we query it:

| name          | birthday   |
| ------------- | ---------- |
| Janos Pasztor | 1984-08-16 |

Let's try it:

```sql
SELECT
  name,
  birthday
FROM
  students;
```

If you've done everything right, you should get something like the table above.

> **Tip**
> You can use `*` instead of specifying the column names explicitly to fetch all columns.
> 
> However, specifying column names explicitly is considered best practice. This is because if your PHP or Java code 
> depends on a column that does not exist, you will get an easy to debug error.

Now, what if we want to select only a part of the whole table? Easy:

```sql
SELECT
  name,
  birthday
FROM
  students
WHERE
  birthday = '1984-08-16';
```

It almost reads like an English sentence. If you need to provide more constraints, you can just append them with the 
`AND` keyword.

All right, now let's delete some data:

```sql
DELETE FROM
  students
WHERE
  birthday = '1984-08-16';
```

This will delete *all* students with the given birth date. Do you see a pattern here?

If you would want to change my birthday, here's how you could do it:

```sql
UPDATE
  students
SET
  birthday = '1984-09-16'
WHERE
  name = 'Janos Pasztor'
  AND
  birthday = '1984-08-16';
```

I'm guessing it won't be too hard to figure out what `ALTER TABLE` and `DROP TABLE` do. The syntax of all SQL 
commands can be looked up in the respective documentations of the chosen SQL server.

> **Exercise** Before you proceed, I recommend going through a few simple cases of database management. Create a few 
> tables, fill them up with data, select certain rows from them, etc. This will come in handy in the next section of 
> this article.

## Making databases relational

Previously we had all data in one table, with no method to use more than one table in a `SELECT`. This is error prone
and leads to data duplication. Let me show you.

Imagine this: you need to build a student database. Every student is in a table and they are free to chose classes 
they want to attend. From our previous experience the solution would be something like this:

```sql
CREATE TABLE students (
    name VARCHAR(255),
    classes VARCHAR(255)
);
```

We would then put the class names in that one `classes` column, delimited by comma. That's problematic for multiple 
reasons. For one, you can only write 255 characters in that column. Two, selecting all students attending a class 
is kinda ugly:

```sql
SELECT
  name
FROM
  students
WHERE
  classes LIKE '%math%';
```

This will look through all rows and do a text search for `math` within the `classes` column. One can only guess how 
bad the performance is going to be.

But that aside, there us a much bigger problem with this setup. Let's assume you had to change the name `math` to 
mathematics in this dataset:

| name | classes |
| ---- | ------- |
| Joe  | literature,math,history |
| Suzy | astrononomy,math,physics |

Go ahead and write a query that will replace `math` with `mathematics`...

...

Having a hard time? It's definitely possible, but I wouldn't call it nice. As you might have figured out from the 
*relational* part in the RDBMS name, modern databases can do *way* better than that.

First, let us create separate tables for students and classes:

```sql
CREATE TABLE students (
  student_id INT,
  student_name VARCHAR(255)
);

CREATE TABLE classes (
  class_id INT,
  class_name VARCHAR(255)
);
```

As you can see, we have given them numeric IDs to make them easier to identify. Going forward, we are not going to 
identify rows by name, but by this generated number that we assign each row.

So now that we have our two tables, we need to connect them somehow. Let's take the simple case. Every student can 
only attend *one* class, but the same class can be attended by multiple students. This is called a `1:n` (one-to-n or one-to-many) 
relation. To accomplish that, we simply add a column to the `students` table where we indicate the class that they 
are attending:

```sql
CREATE TABLE students (
  student_id INT,
  student_name VARCHAR(255),
  class_id INT
);
```

Or visually:

```dotsvg
digraph students {
    node [shape=record;fontname="Open Sans;sans-serif"];
    edge [color="#777777";fontname="Open Sans;sans-serif"];
    rankdir="LR";
    students [label="students|student_id INT|student_name VARCHAR(255)|<sclassid> class_id INT"];
    classes [label="classes|<cclassid> class_id INT|class_name VARCHAR(255)"];
    students:sclassid->classes:cclassid  [taillabel="n";headlabel="1"];
}
```

Simple, right? Too bad we won't be using this. Our original setup stated that one student can attend multiple 
classes, and a class can also be attended by multiple students. That is called an `n:m` (n-to-m or many-to-many) relation.

Unfortunately this means that we need to introduce a connecting table:

```sql
CREATE TABLE students_classes (
  student_id INT,
  class_id INT
);
```

Or visually:

```dotsvg
digraph students {
    node [shape=record;fontname="Open Sans;sans-serif"];
    edge [color="#777777";fontname="Open Sans;sans-serif"];
    rankdir="LR";
    students [label="students|student_id INT|student_name VARCHAR(255)"];
    classes [label="classes|<cclassid> class_id INT|class_name VARCHAR(255)"];
    students_classes [label="students_classes|<class_id> class_id INT|<student_id> student_id INT"];
    students_classes:class_id->classes:class_id  [taillabel="n";headlabel="1"];
    students_classes:student_id->students:student_id  [taillabel="n";headlabel="1"];
}
```

For every student attending one class we will record a row in this table. In the end, you will have as many rows as 
there are lines on this graph:

```dotsvg
digraph {
    node [shape=plaintext];
    Joe -> math;
    Joe -> literature;
    Joe -> history;
    Suzy -> astronomy
    Suzy -> math
    Suzy -> physics
}
```

I will leave it up to you to fill up this dataset with a bit of sample data, let's get right to querying it. So we 
said we wanted to get all students that attended `math` class, right? First, let's select the math class:

```sql
SELECT
  class_id
FROM
  classes
WHERE
  class_name = 'math'
```

Easy, right? Now comes the tricky part. We need to `JOIN` the tables together. More precisely, we'll use an `INNER 
JOIN`, but more on the different join types later.

```sql
SELECT
  students.student_name
FROM
  classes
  INNER JOIN students_classes ON
    students_classes.class_id =
        classes.class_id
  INNER JOIN students ON
    students.student_id =
        students_classes.student_id
WHERE
  class_name = 'math'
```

Wow, that's a lot of code. Let's break it down a bit. So we have your average select from one table, namedly 
`classes`. You then instruct the database server to look for matching rows in `students_classes`, where the 
`class_id` in both tables matches. Then we do the same for the students, where we instruct the match to be on the two
`student_id` columns.

> **Tip** When doing a join, you only have to specify the column name in the `tablename.columnname` form if the 
> column name itself isn't unique.

> **Tip** If you hate typing, you can alias columns like this: `SELECT S.student_name FROM students AS S`

There's a few interesting bits that you might be wondering about though. What if we add the column `class_name` to 
the result? Will it appear only one time or multiple times? Let's look:

| student_name | class_name |
| ------------ | ---------- |
| Joe          | math       |
| Suzie        | math       |

Nothing too surprising so far. But what happens if we remove the `class_name = 'math'` part and select *all* classes?
Here's the result:

| student_name | class_name |
| ------------ | ---------- |
| Joe          | math       |
| Joe          | literature |
| Suzie        | math       |
| Suzie        | astronomy  |

Now *that* may not be obvious. When dealing with a join, SQL databases will return **duplicate rows** if there are 
multiple values in *either* table of the join.

I'll just let that sink in for a bit.

> **Tip** You don't necessarily have to prefix your column names with the tables, but having duplicate column names in
> results leads to confusion. Instead of using prefixed column names, you can also use aliases like this:
> `SELECT student.name AS student_name FROM students;`

## Join types

You may also be wondering: what happens if someone doesn't attend *any* classes? (Play Pink Floyd - Another Brick in 
the Wall in the background.) Well, with an `INNER JOIN`, you're fresh out of luck because it will only select rows 
that have matches in both tables. Let's look at an overview:

| Join type    | Explanation |
| ------------ | ----------- |
| `INNER JOIN` | Only selects rows that are present in both tables. |
| `LEFT JOIN`  | Selects all rows from the *left* table. If data from he *right* table is missing, it is substituted with `NULL`. Will still create row duplications if there is more than rown in the right. |
| `RIGHT JOIN` | Just like `LEFT JOIN`, but will take all rows from the *right* table. |
| `FULL JOIN`  | Will select all rows from both tables, and replace missing values in the other table with `NULL`. |

> **Tip** You can also use the word `JOIN` instead of `INNER JOIN`, but it is better if you explicitly state which 
> join you want to use.

> **Explanation** `NULL` means “has no value”. It does not mean numeric zero.

> **Explanation** In `LEFT JOIN` and `RIGHT JOIN` the left part always means the first, the right means the second 
> table. When there is more than one join, the left part always means the already joined parts, the right means the 
> newly joined table. 

I realize it may be confusing when to use which, so let's go through them, with examples. To do that, we are going to
work with the following simple dataset.

Table a:

| id |
| -- |
| 1  |
| 2  |
| 3  |
| 4  |

Table b:

| id |
| -- |
| 1  |
| 2  |
| 4  |
| 5  |

### INNER JOIN

As discussed before, when using `INNER JOIN`, the only rows that will appear in the output will be the rows that have
matches in both tables.

```sql
SELECT
  a.id,
  b.id
FROM
  a
  INNER JOIN b
    ON a.id=b.id
```

And the result will be:

| a.id | b.id |
| ---- | ---- |
| 1    | 1    |
| 2    | 2    |
| 4    | 4    |

See? No `NULL` values.

### LEFT JOIN

Left join will take all values from the left table, and then tries to find a matching row from the right table.

| a.id | b.id |
| ---- | ---- |
| 1    | 1    |
| 2    | 2    |
| 3    | NULL |
| 4    | 4    |

See? We have a `NULL` value there.

### RIGHT JOIN

Same thing as `LEFT JOIN`, but from the right this time:

| a.id | b.id |
| ---- | ---- |
| 1    | 1    |
| 2    | 2    |
| 4    | 4    |
| NULL | 5    |

### FULL JOIN

Let's take a look:

| a.id | b.id |
| ---- | ---- |
| 1    | 1    |
| 2    | 2    |
| 3    | NULL |
| 4    | 4    |
| NULL | 5    |

> **Remember** Multiple values in either of the tables can result in row duplications.

## In the next article

You have learned to write basic queries, but that's by no means all SQL can do. Writing complex queries, sorting and 
grouping results, and much much more are yet to come in the next installment of this series.
