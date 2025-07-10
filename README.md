### Initial Setup Steps ###

1. Copy the `.env.example` to a new `.env` file in the root directory of the application
2. Run: `docker-compose up` to build and bring up the containers for the first time
3. Run: `docker-compose exec core-integration-php bash` to get into the php container command line (Run artisan and other commands from here)
4. Run: `php artisan key:generate` to generate the application key from within the container


Run: `docker-compose down -v` to take the containers down and remove any named volumes (Delete mysql, node_modules and vendor data)

Run: `php artisan db:seed` to populate your app with fake data

Start at: routes\api.php, config\coreintegration.php

Note: If you hit something like this `: not foundation-php | ./entrypoint.sh: 11:`, you will need to go to the `entrypoint.sh` file and press `[ctrl + shift + p]` type in `Change End of Line Sequence` chose the `LF` option.
