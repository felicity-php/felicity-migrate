## About Felicity Migrate

Felicity Migrate provides migration capability.

## Usage

Felicity Migrate is made to be used from the command line of Felicity Core. You need to have two settings in place to fully use Felicity Migrate in your projects.

### Migration file location

Felicity Migrate needs to know what directories to look in for you migration files/classes. Set a config item in your projects bootstrap as follows:

```php
<?php

use felicity\config\Config;

Config::set('felicity.migrate.locations.dev', dirname(__DIR__) . '/migrations');
```

The `dev` part there can be any key. The key `dev` corresponds to a group that Felicity Migrate will keep track of so you can have multiple locations for migration files. This will is important for composer packages that want to provide migrations. Just make sure you choose a key that's unique to your package.

### Migrations directory

In order for you to make use of the `migrate/make` command, Felicity Migrate needs to know where to put the files. While you can input that location manually every time you want to make a migration, that seems silly, so you can do something like this:

```php
<?php

use felicity\config\Config;

Config::set('felicity.migrate.migrationsDir', dirname(__DIR__) . '/migrations');
```

## Commands

Felicity Migrate has the following commands which are set if you are using Felicity Core which you can run.

### `./felicity migrate/list`

This will list all the migrations that need to be run.

### `./felicity migrate/make`

This will create a migration in your project in the directory you specified.

### `./felicity migrate/up`

This will run any migrations that need to be run.

## License

Copyright 2017 BuzzingPixel, LLC

Licensed under the Apache License, Version 2.0 (the "License");
you may not use this file except in compliance with the License.
You may obtain a copy of the License at [http://www.apache.org/licenses/LICENSE-2.0](http://www.apache.org/licenses/LICENSE-2.0).

Unless required by applicable law or agreed to in writing, software
distributed under the License is distributed on an "AS IS" BASIS,
WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
See the License for the specific language governing permissions and
limitations under the License.
