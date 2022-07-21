## About

This repository is the Answer for the Back End Developer Test

## Instructions to install
1. Copy the files below and paste it into your root working directory (outside 'src' directory).
- docker-compose.yml
- Dockerfile

2. Start the Docker Desktop application for Windows.

3. In root folder working directory, run on terminal:
```shell script
docker-compose up -d
```
4. For Laravel application:
http://localhost:8000/

5. For database (phpmyadmin):
http://localhost:8008/

Credentials

Username: root

Password: password

6. Create a database name 'mars_trading_db' on phpmyadmin.

7. Move to 'src' directory and run on terminal:
```shell script
composer install
php artisan migrate
```

8. To down the server, run this command on terminal:
```shell script
docker-compose down
```

9. To start again run this command on terminal:
```shell script
docker-compose up -d
```


## API Endpoint
Please see the directory 'docs and screenshots of api endpoint'



## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
