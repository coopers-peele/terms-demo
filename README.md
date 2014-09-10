CPTermsBundle demo app 
======================

A Symfony2 app used to demonstrate the features of the [CPTermsBundle](https://github.com/coopers-peele/terms-bundle).

Requirements
------------
In order to use the built-in Phing build script, you need to install [phing](http://www.phing.info/). Alternatively, just run the various steps your self.

Installation
------------

Clone the repo:

```
git clone https://github.com/coopers-peele/terms-demo.git
```

Build the app. The `bin` dir includes a phing build script with the following targets:

* **build**: "production-like" build, with updates of composer packages.
* **build-all**: "development" build, where composer will udpate all requirements to the latest alloed versions.

``` 
cd terms-demo
phing -f bin/build.xml [build|build-all]
```

Create the database.

```
php app/console propel:sal:build
php app/console propel:sql:insert --force
```

Create a virtual host in your web server and point its document root to the app's `web` directory.

