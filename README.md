## Install prerequisites

- [Docker](https://docs.docker.com/engine/install)
- [Docker Compose](https://docs.docker.com/compose/install)
- [Make](https://linuxhint.com/make-command-linux)

## Installation

Once every prerequisite above are ready, we can just invoke our [Make](https://linuxhint.com/make-command-linux) commands.

To simply begin, enter the following command to start all services:
```sh
make up
```
This will initialize the .env file and create the container images for our Laravel app and MySQL 8. The data migration will also automatically perform on starting the Laravel app.

To view other [Make](https://linuxhint.com/make-command-linux) commands, enter the following: 
```sh
make help
```

## Documentation

After successfully running the services, visit ```http://localhost:8000/docs``` to view the API documentation. [Scribe](https://scribe.knuckles.wtf/laravel) was used to generate all the endpoint documentations. 

You can also enter the following command to regenerate documentation:
```sh
make docs
```

## Testing

SQLite is used by default for the Unit and Feature testing. You can enter the following command to perform the tests:
```sh
make test
```
