# Your web application

This is a skeleton of your web application wired up with the Piccolo Framework. It's called Piccolo because it's 
really, really small. Your code should, as per S.O.L.I.D., never depend on the framework directly, and that's what 
this does.

## Installing

The installation is simple. You can either `git clone` and then run `composer update` or simply create a new project:

```
composer create-project opsbears/piccolo-skel -s dev
```

## Using

To use it, simply configure the DIC to your liking in the `config/config.php` file and create your controllers and 
what not in `src/App/Web`. The web-independent parts, like your business logic, should obviously be outside the `Web`
folder, in case you need to build a CLI tool for it later.

You may notice that the existing sample code is under the `AcmeCorp` namespace, which you should change. You can do 
that by changing composer.json and the code itself, then running `composer dump-autoload`.

The dev server can be started from the command line by running `vendor/bin/phing devserver`.

## Frontend build

This repository includes some basic tools for building the frontend stack. To run these, you will require npm and 
rubygems on your computer. Installing the frontend tools is easy:

```
vendor/bin/phing frontend-install
```

This will install all the tools *locally* into your working copy. They are, by default, excluded in the `.gitignore`
file, so you don't have to worry about committing them.

Running the frontend build can similarly be done by using phing:

```
vendor/bin/phing frontend-build
```

This will run the frontend build (sass and uglify.js).

## Deployment

To deploy this application, you should first configure your webserver to direct all requests that are otherwise not 
found to `index.php` in the `htdocs` folder. That will ensure that routing works properly. (We have provided examples
in the config directory.)

Once the webserver is up and running, copy the project to the webserver and adjust `config/local.config.php` to match
your production settings. That's it! Your site should be up and running! 

## A few good talks to watch

- [Agility and Architecture by Robert C. Martin](https://www.youtube.com/watch?v=0oGpWmS0aYQ)
- [The Future of Programming by Robert C. Martin](https://www.youtube.com/watch?v=9Xy3QC7yxJw)
- [The Transformation Priority Premise by Robert C. Martin](https://www.youtube.com/watch?v=B93QezwTQpI)
