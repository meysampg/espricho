# Espricho [WIP]
[![Maintainability](https://api.codeclimate.com/v1/badges/b3022c87609aaee11faa/maintainability)](https://codeclimate.com/github/meysampg/espricho/maintainability)

Espricho is a lightwieght framework for php7, based on 
[Symfony](https://symfony.com) components with a built-in 
modular system support.

## Prevision!
Espricho is heavily inspired from [Laravel](http://laravel.com/) 
and [Yii2](http://yiiframework.com/). Its modularity is similar to
Yii2 but thanks to the Symfony DI container, it has
a powerful container like Laravel! Espricho is the Persian name of 
swallow bird (in Kermani dialect).

## Mafsho
`mafsho` is a command line tool which provide a lot of functionality
like generators to work more easier with Espricho. It's a Farsi word
means bag (in Kermani dialect). You can run it with `php mafsho` command.

## Structure
Espricho has a structure like this:
```
├── Bootstrap
├── Components
├── Configs
├── Console
├── Controllers
├── Databases
├── mafsho
├── Models
├── Modules
├── Public
└── Runtime
```
We talk about each one in continue.

## Configurations VS Environmental Variables
Espricho supports definition of environmental variables (variables which are
depended on the running environment). You should put them on `.env` (or `.env.dist` 
which `dist` is an application stage) file. It's a good idea if all keys on the `.env`
file be upper case and start with the section name. For example, all ElasticSearch keys
starts with `ELASTICSEARCH_` prefix. 

On other side, you can define application
level configurations in the `Config/*.yaml` files. Finally both of this 
configurations are accessible from `sys()->getConfig('dot.notationed.key', 'default')`.

### sys.yaml
On `sys.yaml` file, you could set the boot parameters of the system. As the 
main result, you could define system module loaders under the `loader` key.
```yaml
sys:
  name: Espricho
  version: 1.0
  max_log_files: 10
  loader: 
    - auth
    - db
    - modules
    - redis
    - elasticsearch
```

### Database Configurations
The database configuration should be set on `.env` the root of the project. 
A sample for its content is similar
to this:
```dotenv
# Database Configurations
DB_DRIVER=mysql
DB_HOST=localhost
DB_PORT=3306
DB_USERNAME=root
DB_PASSWORD=root
DB_DATABASE=db_name
```

## Modules
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
