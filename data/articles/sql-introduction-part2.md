Title:      Introduction to SQL databases
Subtitle:   Part 2
Author:     janoszen
Published:  0000-00-00
Categories: basics
Excerpt:    Whether you are running a webapp, a financial system or a game, you need some method of storing your data.
            SQL allows you to query most traditional databases, like MySQL or PostgreSQL. Let's take a look.
Social:     /images/sql-introduction-part2/social.png
Decor:      /images/sql-introduction-part2/decor.png
Decor2x:    /images/sql-introduction-part2/decor-2x.png

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

> **Tip** This article starts off easy, but can be a bit overwhelming when read at once. Take your time and 
> experiment with what you have learned for the maximum benefit. 

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
only attend *one* class, but the same class can be attended by multiple students. This is called a `1:n` (one-to-n) 
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
classes, and a class can also be attended by multiple students. That is called an `n:m` (n-to-m) relation.

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
| `INNER JOIN` or `CROSS JOIN` | Only selects rows that are present in both tables. |
| `LEFT JOIN`  | Selects all rows from the *left* table. If data from he *right* table is missing, it is substituted with `NULL`. Will still create row duplications if there is more than rown in the right. |
| `RIGHT JOIN` | Just like `LEFT JOIN`, but will take all rows from the *right* table. |
| `FULL JOIN`  | Will select all rows from both tables, and replace missing values in the other table with `NULL`. |

> **Tip** You can also use the word `JOIN` instead of `INNER JOIN`, but it is better if you explicitly state which 
> join you want to use.

> **Explanation** `NULL` means “has no value”. It does not mean numeric zero.

> **Explanation** In `LEFT JOIN` and `RIGHT JOIN` the left part always means the first, the right means the second 
> table. When there is more than one join, the left part always means the already joined parts, the right means the 
> newly joined table. 

I realize it may be confusing when to use which, so let's go through them, with examples.

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

## Keys and constraints

So far so good. However, you may have realized that nothing stops us from inserting the same ID twice. What's worse, 
we could reference a student that does not exist, or a class that does not exist.

For example:

```sql
INSERT INTO students (
  id,
  student_name
) VALUES (
  1,
  'Joe'
);

INSERT INTO students (
  id,
  student_name
) VALUES (
  1,
  'Suzy'
);

INSERT INTO students_classes (
  student_id,
  class_id
) VALUES (
  51234,
  96543
);
```

This could lead to bugs and unforeseen problems in your programs, and huge discrepancies in business analytics. So 
let's make sure this doesn't happen.

### Unique keys

First of all, let's learn how to create a constraint to allow only one value in the `student_id` column:

```sql
ALTER TABLE students ADD CONSTRAINT u_student_id UNIQUE (student_id);
```

Or if you would create the table anew:

```sql
CREATE TABLE students (
  student_id INT,
  student_name VARCHAR(255),
  CONSTRAINT u_student_id UNIQUE (student_id)
);
```

> **Be careful!** Depending on your server, unique keys may allow multiple rows with `NULL` values!

### Primary keys

However, there's a problem. Columns in unique keys are still allowed to have `NULL` values. While we can work around 
that using a `NOT NULL` statement on the column, there is a better way to describe the primary column in a table is 
to add a `PRIMARY KEY`.

```sql
ALTER TABLE students ADD CONSTRAINT pk_students PRIMARY KEY (student_id);
```

Or for new tables:

```sql
CREATE TABLE students (
  student_id INT,
  student_name VARCHAR(255),
  CONSTRAINT pk_students PRIMARY KEY (student_id)
);
```

### Primary keys vs. unique keys

Let's look at the difference:

|                                  | Primary key     | Unique key                            |
| -------------------------------- | --------------- | ------------------------------------- |
| Can contain more than one column | Yes             | Yes                                   |
| Can contain `NULL` value         | No (by default) | Yes (unless the column is `NOT NULL`) |
| More than one key in a table     | No              | Yes                                   |

There are a few more differences, but most of those are specific to the SQL server you are using.

### Making the database fast (indexes)

Before we get into the data consistency issue, let's take a short detour. You are probably playing around with small 
tables, so you most likely didn't notice speed problem. If you are moving  to larger tables, you'll quickly notice 
that your database becomes incredibly slow.

As you might have guessed, that's what indexes are for. By default databases store all data in one big chunk, sorted 
by the primary key (which is an index by the way). Without proper indexes, the database may need to read the full table 
in order to get the data you need. This is called a *full table scan*.

We can speed up queries to a table by adding indexes. However, while **indexes speed up reads, they slow down writes**, 
since the indexes need to be updated. Lets do that:

```sql
CREATE INDEX i_student_id ON students_classes (student_id);
```

As a general rule, adding indexes should be added as needed, by analyzing the queries that are slow. There are a 
multitude of tools to monitor slow queries, but they are database specific, so we won't go into them here. However, 
you *should* monitor your database servers for queries that perform poorly and fix them by adding the proper indexes.

### Foreign keys

Returning to the data consistency problem, we still have the issue of referencing values that do not exist. 
(Referencing a non-existing class, etc.) We also have a tool for that, called foreign keys.

Foreign keys basically mean “only allow values that appear in the other table too”. In order to create a 
foreign key, you will need to add an index (normal, unique key or primary key) on the column that you want to 
reference. After that, you can simply create a constraint like we did before:

```sql
ALTER TABLE students_classes
  ADD CONSTRAINT fk_students_student_id
    FOREIGN KEY (student_id)
    REFERENCES students(id);

ALTER TABLE students_classes
  ADD CONSTRAINT fk_students_class_id
    FOREIGN KEY (class_id)
    REFERENCES classes(id);
```

If you now try to insert a value that is not represented in the referenced table, you should get an error.

One question remains: what happens if you *change* or *delete* a linked value? The SQL standard defines five actions:

| `CASCADE` | If the row is updated, the linked value is updated as well. If it is deleted, the referencing row is also deleted. |
| `RESTRICT` | Block the change or deletion if the row is referenced. |
| `NO ACTION` | Ignore the change or deletion. This will result in an invalid value in the referencing row. |
| `SET NULL` | Set the referencing row to `NULL` on change/deletion. |
| `SET DEFAULT` | set the referencing row to the default value on change/deletion. |

Using it is pretty simple:

```sql
ALTER TABLE students_classes
  ADD CONSTRAINT fk_students_student_id
    FOREIGN KEY (student_id)
    REFERENCES students(id)
    ON UPDATE CASCADE 
    ON DELETE RESTRICT;
```

This basically translates to: “Only allow values in `students_classes.student_id` that also appear in 
`students.student_id`. When the contents of the `students.student_id` field are updated, also update 
`students_classes.student_id`. When the row in `students` that we are referencing is deleted, block that deletion
.”

Still reads like an English sentence, right?

## Advanced queries

Well, now we've created an unholy mess with our tables, time to get some more data out of it. Here's some advanced 
techniques for getting out the data you have successfully deposited in your database.

### Sorting the results

Sorting the results is easy, but may also need an index to be fast. Let's do just that:

```sql
SELECT
  class_id,
  class_name
FROM
  classes
ORDER BY
  class_name ASC;
```

### Fetching only the first X rows

In addition to `ORDER BY`, you can also limit the number of rows you get. Unfortunately the syntax is different from 
database to database. The following queries will fetch 10 classes, starting at class 20.

MySQL, PostgreSQL, SQLite:

```sql
SELECT
  class_id,
  class_name
FROM
  classes
ORDER BY
  class_name ASC
LIMIT 10 OFFSET 20
```

Microsoft SQL Server, Oracle:

```sql
SELECT
  class_id,
  class_name
FROM
  classes
ORDER BY
  class_name ASC
OFFSET 20 FETCH NEXT 10 ROWS ONLY
```

Yeah, so much for SQL being a “standard”

### Grouping results

We've talked about the duplication issue with joins before. One nice way of getting rid of said duplications is to 
use the `GROUP BY` functionality. As the name says, it will take the specified column and group the results by that 
column.

If we wanted to count the number of students attending each class for example, we'd do it like this:

```sql
SELECT
  classes.class_name,
  COUNT(*)
FROM
  classes
  INNER JOIN students_classes ON
    students_classes.class_id =
        classes.class_id
  INNER JOIN students ON
    students.student_id =
        students_classes.student_id
GROUP BY
  classes.class_name
```

This will aggregate all the rows by the class name, and then provide a count of how many items the group has.

### Subqueries

TBD

## Let's talk about security

SQL has one important aspect, especially when talking about web application. Namedly, dealing with user input. 
Imagine this query in any modern web language:

```java
string sql = 'SELECT
  id
FROM
  users
WHERE
  username="' + username + '"
  AND
  password="' + password + '"
```

Besides the fact that you should never ever save passwords in plain text, there is a much bigger problem. What if I 
provide `username = 'admin'` and `password='" OR 1=1'` as input? Well, here's the resulting query:

```sql
SELECT
  id
FROM
  users
WHERE
  username="admin"
  AND
  password="" OR 1=1
```

This is called an SQL injection and will lead to your admin user being selected, regardless of the password supplied.
There are many ways of doing this, but in the end, they lead to leaked data (for example your whole username/password
database), privilege escalation (logging in as admin) or worst case scenario, deletion of data (inject a `DROP 
DATABASE`). Ouch.

So you need to protect against SQL injection. And don't even try writing your own “defense” functions, 
the specifics depend on the SQL server type, version and implementation details. Instead, use prepared statements.

Prepared statements consist of two parts. First you prepare your query, for example:

```sql
SELECT
  id
FROM
  users
WHERE
  username=?
  AND
  password=?
```

And then you execute the query with the parameters that will be safely replaced into the question marks. Check your 
programming language for details on how to properly do this.

## Conclusion

As you can see, SQL is a very powerful and complex tool. It can start off easy, but lead to mile-long queries with 
execution times of hours, or even days.

Try to keep it simple though. Sometimes you don't need to solve everything in one query. You can extract parts of the
data, and put it in a different table for further processing. In fact, this is a really good way of doing it, if you 
don't need the result right away. What's more, there are tools for that, called ETL (Extract, Transform and Load). But
that's a story for another day.