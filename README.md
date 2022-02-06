# Cireme

Contact center CRM software.

## Local Development Environment

We use [Docker Compose](https://docs.docker.com/compose/) to create local development environment with [PHP-CLI](https://hub.docker.com/_/php), [PostgreSQL](https://hub.docker.com/_/postgres), and [Redis](https://hub.docker.com/_/redis) image services.

The project directory is mounted to the `app` service for easy development flow like any regular PHP project. The Xdebug configured to connect to the Docker host at the default port `9003` on each request.

Create the development environment:

```shell
docker compose up
```

Or with `-d` parameter to run it in the background:

```shell
docker compose up -d
```

The Laravel configuration stored in `.env.dev` file, to make changes after running the development environment, we need to build and restart the `app` service.

Build the `app` service image:

```shell
docker compose build app
```

and then restart the `app` service:

```shell
docker compose restart app
```

The Laravel `app` service is exposed to Docker host at `8080` port, we can browsed it at http://localhost:8080.

The supporting services like PostgreSQL and Redis are exposed to Docker host at `54320` and `63790` ports.

## Maintenance

### Create Administrator User

To create a user with administrator(super user) privilege, run the create admin CLI command:

```shell
php artisan create:admin <EMAIL_ADDRESS> <NAME>
```

or within the `app` container:

```shell
docker compose exec app php artisan create:admin <EMAIL_ADDRESS> <NAME>
```

