# Weather App

### API
- Navigate to `/api` folder
- Ensure version docker installed is active on host
- Copy .env.example: `cp .env.example .env`
- Start docker containers `docker compose up` (add `-d` to run detached)
- Connect to container to run commands: `docker exec -it fullstack-challenge-app-1 bash`
  - Make sure you are in the `/var/www/html` path
  - Install php dependencies: `composer install`
  - Setup app key: `php artisan key:generate`
  - Migrate database: `php artisan migrate` 
  - Seed database: `php artisan db:seed`
  - Run tests: `php artisan test`
- Visit api: `http://localhost:81`

### Frontend
- Navigate to `/frontend` folder
- Ensure nodejs v18 is active on host
- Install javascript dependencies: `npm install`
- Run frontend: `npm run dev`
- Visit frontend: `http://localhost:5173`


## PHP Stan Analysis

<img width="712" alt="Screenshot 2023-03-07 at 12 13 07" src="https://user-images.githubusercontent.com/12198695/223409970-82d9c9d5-ca03-4694-ae84-b1bf86df7929.png">


## PHP Insights Analysis

<img width="542" alt="Screenshot 2023-03-07 at 12 24 39" src="https://user-images.githubusercontent.com/12198695/223410057-aa446a93-17da-4bb1-9010-f39660319b81.png">
