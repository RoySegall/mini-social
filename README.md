[![Build Status](https://travis-ci.org/RoySegall/mini-social.svg?branch=master)](https://travis-ci.org/RoySegall/mini-social)

# Mini social.

Welcome to the mini social project. A home task.


## Set up backend

You'll need [Rethinkdb](http://rethinkdb.db) up and running:

```bash
rethikndb --http-port 8090
```

Now, let's set up the data

```bash
cd server
composer install

php console.php social:install
```

Now you got the data ready to go. In case you messed up with the data and want
to go back to the original data just do:

```bash
php console.php social:install --migrate_only true
```

This will only reset the migrated data.

The last thing is to get the backend up and running:

```bash
php -S localhost:8081
```

That's it. Now you can enjoy with a slice of :pizza: and :pineapple:

### Tests

In order to run tests you'll need phpunit:
```php
compsoer instal --dev
```

Running the tests is easy:
```bash
bash tests.php
```

## Frontend

Well, though the backend is awesome the front is what matter.

```bash
cd mini-social
yarn # or npm install
```

This might take a while. After the installation has accomplished run:
```bash
yarn start
```

That's it.

The list of users is in `server/migratoins.yml`

_Optional:_ By default, the backend run at http://localhost:8081 you can change
the address of the backend in `src/settings.php`


