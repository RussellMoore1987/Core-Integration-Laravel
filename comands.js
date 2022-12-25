// @ to build this project
    // composer create-project laravel/laravel="8.4.*" laravel_resource

// @ start up application (run/serve)
    // php artisan serve
    // http://localhost:8000/

// @ Run Tests Laravel 
    // php artisan test
    // vendor/bin/phpunit // dose not work in work interment with out
        // docker-compose exec practice-app bash
    // vendor/bin/phpunit -h
        // help info

    // To create a new test case, use the make:test Artisan command. By default, tests will be placed in the tests/Feature directory:
        // php artisan make:test UserTest
    // If you would like to create a test within the tests/Unit directory
        // php artisan make:test UserTest --unit

// @ list of routes
    // php artisan route:list

// @ php artisan
    // php artisan help make:controller

    // php artisan make:controller PostsController
    // php artisan make:model Post
    // php artisan make:migration create_posts_table

    // php artisan tinker

// @ run migration
    // php artisan migrate

    // make 
        // php artisan make:migration create_projects_table --create
        // php artisan make:migration create_case_studies_table --create
        // php artisan make:migration create_categories_table --create
        // php artisan make:migration create_tags_table --create
        // php artisan make:migration create_skills_table --create
        // php artisan make:migration create_skill_types_table --create
        // php artisan make:migration create_work_history_table --create
        // php artisan make:migration create_work_history_type_table --create
        // php artisan make:migration create_post_table --create
        // php artisan make:migration create_experience_table --create
        // php artisan make:migration create_content_table --create
        // php artisan make:migration create_images_table --create
        // php artisan make:migration create_connection_table --create

    // place holder
        // php artisan migrate:fresh

    // if adding colum make sure to create a new migration for additional database columns

    // * migrate
        // migrate:fresh        Drop all tables and re-run all migrations
        // migrate:install      Create the migration repository
        // migrate:refresh      Reset and re-run all migrations
        // migrate:reset        Rollback all database migrations
        // migrate:rollback     Rollback the last database migration
        // migrate:status       Show the status of each migration

    // * migration info 
        // ? https://laravel.com/docs/8.x/migrations#column-method-string

// @ make factory
    // php artisan make:factory PostFactory --model=Post
    // php artisan make:factory ProjectFactory --model=Project

    // faker
    // ? https://github.com/fzaninotto/Faker

// @ db seed
    // php artisan db:seed
    // php artisan migrate:refresh --seed // rebuilds tables and seeds them
    // php artisan migrate:fresh --seed // best one

// @ make controller
    // php artisan make:controller PostsController

    // make resource controller
        // php artisan make:controller projectsController --resource

// @ make many things
    // php artisan help make:model
    // Description:
    //   Create a new Eloquent model class

    // Usage:
    //   make:model [options] [--] <name>

    // Arguments:
    //   name                  The name of the class

    // Options:
    //   -a, --all             Generate a migration, seeder, factory, and resource controller for the model
    //   -c, --controller      Create a new controller for the model
    //   -f, --factory         Create a new factory for the model
    //       --force           Create the class even if the model already exists
    //   -m, --migration       Create a new migration file for the model
    //   -s, --seed            Create a new seeder file for the model
    //   -p, --pivot           Indicates if the generated model should be a custom intermediate table model
    //   -r, --resource        Indicates if the generated controller should be a resource controller
    //       --api             Indicates if the generated controller should be an API controller
    //   -h, --help            Display help for the given command. When no command is given display help for the list command
    //   -q, --quiet           Do not output any message
    //   -V, --version         Display this application version
    //       --ansi|--no-ansi  Force (or disable --no-ansi) ANSI output
    //   -n, --no-interaction  Do not ask any interactive question
    //       --env[=ENV]       The environment the command should run under
    //   -v|vv|vvv, --verbose  Increase the verbosity of messages: 1 for normal output, 2 for more verbose output and 3 for debug


// @ Tinker commandline
    // Psy Shell v0.10.8 (PHP 7.3.21 — cli) by Justin Hileman     
    // >>> $Project = new App\Models\Project();
    // => App\Models\Project {#4369}
    // >>> $Project
    // => App\Models\Project {#4369}
    // >>> $Project->title = 'Gogo!!!';
    // => "Gogo!!!"
    // >>> $Project->description = 'Hi there, I got a description.';
    // => "Hi there, I got a description."
    // >>> $Project->save();
    // => true



    // $Project = new App\Models\Project::find(51);

// @ retrieving models
    // # App\Models\Project::find(51);
        // php artisan tinker 
            // >>> App\Models\Project::find(51);
            // => App\Models\Project {#4376
            //     id: 51,
            //     title: "Gogo!!!",
            //     description: "Hi there, I got a description.",
            //     created_at: "2021-06-23 22:39:32",
            //     updated_at: "2021-06-23 22:39:32",
            // }
            // >>> App\Models\Project::find(52);
            // * => null

        // App\Models\Project::find(51); // by id
        // App\Models\Project::find([33,44,45,51]); // by ids
        // App\Models\Project::findOrFail(52); // by id or fail
        // $Project = Project::where('completed_at', null)->firstOrFail();
        // App\Models\Project::all();
        // App\Models\Project::with('tasks'); // gives sub set data in same result set
        // App\Models\Payee::with(["paystubs" => function($query){$query->with(["line_items"]);}])->get(); // payee, pay stubs, and pay stub line items
        // $CaseStudies = App\Models\CaseStudy::with(['tags','categories','images'])->get();
        // App\Models\Project::first();
        // App\Models\Project::where('completed_at', null)->get();
        // $Project = App\Models\Project::where('completed_at', null)->first();
        // $Project = App\Models\Project::firstWhere('completed_at', null);
        
        // $from = date('2018-01-01');
        // $to = date('2019-05-02');
        // App\Models\Project::whereBetween('completed_at', [$from, $to])->get();
        // App\Models\Project::whereBetween('completed_at', ['2018-01-01', '2019-05-02'])->get();

        // $Projects = App\Models\Project::where('completed_at', null)
        //     ->orderBy('title')
        //     ->take(10)
        //     ->get();
        // $Projects = App\Models\Project::where('completed_at', null)->orderBy('title')->take(3)->get();
        // $Projects = App\Models\Project::where('completed_at', null)->orderByDesc('title')->limit(3)->get();

        // Aggregations
        // $count = App\Models\Project::where('completed_at', null)->count();
        // $max = App\Models\Project::where('completed_at', null)->max('updated_at');

        // TODO: try later format into existing architecture
            // ? https://laravel.com/docs/8.x/eloquent#advanced-subqueries
            // use App\Models\Destination;
            // use App\Models\Flight;

            // Destination::addSelect(['last_flight' => Flight::select('name')
            //     ->whereColumn('destination_id', 'destinations.id')
            //     ->orderByDesc('arrived_at')
            //     ->limit(1)
            // ])->get();

            // Destination::orderByDesc(
            //     Flight::select('arrived_at')
            //         ->whereColumn('destination_id', 'destinations.id')
            //         ->orderByDesc('arrived_at')
            //         ->limit(1)
            // )->get();

    // # Inserting & Updating Models
        // * Inserting
            // $flight = new Flight;
            // $flight->name = $request->name;
            // $flight->save();

            // $flight = Flight::create([
            //     'name' => 'London to Paris',
            // ]);

        // * Updating
            // $flight = Flight::find(1);
            // $flight->name = 'Paris to London';
            // $flight->save();

            // mass update
            // Flight::where('active', 1)
            //     ->where('destination', 'San Diego')
            //     ->update(['delayed' => 1]);
            // ! Caveat with it
            // ? https://laravel.com/docs/8.x/eloquent#mass-updates

    // # deleting
        // $flight = Flight::find(1);
        // $flight->delete();

        // Flight::destroy(1);
        // Flight::destroy(1, 2, 3);
        // Flight::destroy([1, 2, 3]);
        // Flight::destroy(collect([1, 2, 3]));


    // # other 
        // $user = DB::table('users')->where('name', 'John')->first();
        // $user->email;

        // $titles = DB::table('users')->pluck('title');
        // foreach ($titles as $title) {
        //     echo $title;
        // }

        // if (DB::table('orders')->where('finalized', 1)->exists()) {
        //     // ...
        // }
        // if (DB::table('orders')->where('finalized', 1)->doesntExist()) {
        //     // ...
        // }

        // * The ability to build queries and then utilize them
        // $query = DB::table('users')->select('name');
        // $users = $query->addSelect('age')->get();

        // $users = DB::table('users')
        //     ->where('votes', '=', 100)
        //     ->where('age', '>', 35)
        //     ->get();

        // $users = DB::table('users')
        //     ->where('name', 'like', 'T%')
        //     ->get();

        // $users = DB::table('users')->where([
        //     ['status', '=', '1'],
        //     ['subscribed', '<>', '1'],
        // ])->get();

        // $users = DB::table('users')
        //     ->where('votes', '>', 100)
        //     ->orWhere('name', 'John')
        //     ->get();

        // If you need to group an "or" condition within parentheses, you may pass a closure as the first argument to the orWhere method:
        // $users = DB::table('users')
        //     ->where('votes', '>', 100)
        //     ->orWhere(function($query) {
        //         $query->where('name', 'Abigail')
        //               ->where('votes', '>', 50);
        //     })
        // ->get();
        // The example above will produce the following SQL:
        // select * from users where votes > 100 or (name = 'Abigail' and votes > 50)

        // $users = DB::table('users')
        //    ->where('name', '=', 'John')
        //    ->where(function ($query) {
        //        $query->where('votes', '>', 100)
        //              ->orWhere('title', '=', 'Admin');
        //    })
        //    ->get();
        // select * from users where name = 'John' and (votes > 100 or title = 'Admin')

        // json
        // $users = DB::table('users')
        //     ->where('preferences->dining->meal', 'salad')
        //     ->get();

        // whereBetween / orWhereBetween
        // $users = DB::table('users')
        //    ->whereBetween('votes', [1, 100])
        //    ->get();

        // whereNotBetween / orWhereNotBetween
        // $users = DB::table('users')
        //     ->whereNotBetween('votes', [1, 100])
        //     ->get();

        // whereIn / whereNotIn / orWhereIn / orWhereNotIn
        // $users = DB::table('users')
        //     ->whereIn('id', [1, 2, 3])
        //     ->get();

        // whereNull / whereNotNull / orWhereNull / orWhereNotNull
        // $users = DB::table('users')
        //     ->whereNull('updated_at')
        //     ->get();

        // whereDate / whereMonth / whereDay / whereYear / whereTime
        // $users = DB::table('users')
        //     ->whereDate('created_at', '2016-12-31')
        //     ->get();
        // $users = DB::table('users')
        //     ->whereMonth('created_at', '12')
        //     ->get();
        // $users = DB::table('users')
        //     ->whereDay('created_at', '31')
        //     ->get();
        // $users = DB::table('users')
        //     ->whereYear('created_at', '2016')
        //     ->get();
        // $users = DB::table('users')
        //     ->whereTime('created_at', '=', '11:20:45')
        //     ->get();

        // $users = DB::table('users')
        //    ->whereExists(function ($query) {
        //        $query->select(DB::raw(1))
        //             ->from('orders')
        //             ->whereColumn('orders.user_id', 'users.id');
        //    })
        //    ->get();
        // select * from users
        // where exists (
        //     select 1
        //     from orders
        //     where orders.user_id = users.id
        // )

        // Auto-Incrementing IDs
        // If the table has an auto-incrementing id, use the insertGetId method to insert a record and then retrieve the ID:
        // $id = DB::table('users')->insertGetId(
        //     ['email' => 'john@example.com', 'votes' => 0]
        // );

        // Debugging
        // DB::table('users')->where('votes', '>', 100)->dd();
        // DB::table('users')->where('votes', '>', 100)->dump();

// @ retrieving models from collections
    // $Projects = Project::all();
        // $Projects[0];
        // $Projects->first();
        // $Projects->count();
        // ? https://laravel.com/docs/8.x/eloquent-collections#available-methods

        // reject
            // $Projects = App\Models\Project::all();
            // $Projects->count(); // 50
            // $Projects = $Projects->reject(function ($Project) {
            //     return $Project->completed_at;
            // });
            // $Projects->count(); // 23
            // gives back completed_at = null

// @ Laravel project, it's fresh start - work Fox
    // Run the docker-compose build command from the root directory
    // Then run the docker-compose up command to bring the docker containers up
    // Navigate to the application page by going to http://localhost:8000/
    // You may bring up the terminal for the docker container to execute commands by using this command: docker-compose exec practice-app bash
        // docker-compose exec practice-app bash
        // php artisan
    // composer install
    // msql = docker-compose exec practice-mysql bash


    // docker-compose build
    // docker-compose up
    // http://localhost:8000/

    // docker-compose exec practice-app bash
    // php artisan

    // docker-compose exec practice-mysql bash

    // php artisan migrate:fresh

    // * Work Prosses
        // docker-compose build // * if docker file changed
            // docker-compose up 
        // docker-compose down -v // wipes all the docker volumes 
            // docker-compose up 
        // docker-compose up // bring environment up
        // http://localhost:8000/

        // docker-compose exec practice-app bash // remotes me in to the container
            // php artisan
        // docker-compose exec practice-mysql bash // mysql container 

        // php artisan migrate:fresh
        // php artisan migrate:fresh --seed
        // php artisan db:seed

        // Branching / Example Branching and Merging
            // v1.12.0/v1.12.0
            // PA-987

            // Branched off of:
            // v1.12.0/v1.12.0 => v1.12.0/PA-987

            // Before Pull Request OR QA:
            // Merge
            // v1.12.0/v1.12.0 => v1.12.0/PA-987

            // Pull Request Approved/Merge
            // v1.12.0/PA-987 => v1.12.0/v1.12.0
        
        // * compile web packet and changes sass, JavaScript, css
            // npm run watch 
            // npm run dev (or) npm run prod


            // php artisan test --coverage-html ./phpunit-coverage-report

 // @ Problems
    // TODO: get link and error message********
    // app\Providers\AppServiceProvider.php
    // Schema::defaultStringLength(191);

    // Cannot declare class CreateWorkHistoryTypesTable, because the name is already in use laravel 2021
        // ? https://stackoverflow.com/questions/54765710/error-migrations-cannot-declare-class-x-because-the-name-is-already-in-use/54765856
   
    // Something else connected to MySQL not allowing me to run the docker
        // Solution
            // Went to services and stopped wampmysql64