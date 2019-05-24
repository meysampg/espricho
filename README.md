# Espricho [WIP]
Espricho is a lightwieght framework for php7, based on 
[Symfony](https://symfony.com) components with a built-in 
modular system support.

### Prevision!
Espricho is heavily inspired from [Laravel](http://laravel.com/) 
and [Yii2](http://yiiframework.com/). Its modularity is similar to
Yii2 but thanks to the Symfony DI container, it has
a powerful container like Laravel!

### Structure
Espricho has a structure like this:
```
├── Bootstrap
├── Components
├── Configs
├── Controllers
├── mafsho
├── Models
├── Modules
├── Public
└── Runtime
```
We talk about each one in continue.

### Database Configurations
The database configuration should be set on `db.yaml` on `Configs`
folder of project's root. A sample for its content is similar
to this:
```yaml
db:
  driver: mysql
  host: localhost
  port: 3306
  username: root
  password: toor
  database: espricho
```

### Modules
For defining module, you must put your module under the 
`Modules` folder and define your structure on it. A sample
structure can be something like this:
```
Modules
└── Shop
    ├── Configs
    │   └── routes.yaml
    └── Controllers
        └── ProductController.php
```
Each module has its own route definition rules and they must 
be defined on `routes.yaml` file under `Configs` category. 
After definition of module, you can register it on `modules.yaml`
in `Configs` folder of the project's root. A sample
content for this configuration file is:
```yaml
modules:
  shop:
    folder: Shop
    route_prefix: sh
```
Each module can have its submodules and they must be defined on 
`modules.yaml` file in `Configs` folder of the module directory.
Definition of routes is similar.
