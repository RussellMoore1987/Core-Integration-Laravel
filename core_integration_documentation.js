// 


// @ API
    // # scope helper
        // This will help you to see what processing is going on behind the scenes 
        // You do have to be in the right environment for this to work
        // URL: http://127.0.0.1:8000/api/v1/projects?includes=tags,categories,images,authors&hfsdhjf=ffff&title=tiyf&orderBy=title::DECS,roles,fjkdhskjhkjfd&perPage=20&page=2
        // DD: dd('paramsAccepted', $this->paramsAccepted, 'paramsRejected', $this->paramsRejected, 'includesAccepted', $this->includesAccepted, 'acceptableParameters', $this->acceptableParameters);
    // # Index page
    // How to get to the index page.
        // * You can set it up in multiple ways
            // Explicit example
            // Route::any("v1/", [GlobalAPIController::class, 'indexPage']);

            // Implicit example
            // Route::any("v1/{class?}/{id?}", [GlobalAPIController::class, 'processRequest']);

        // * Index is built off of
            // config('coreintegration.acceptedclasses')
                // ex 
                // 'acceptedclasses' => [
                //     'caseStudies' => 'App\Models\CaseStudy',
                //     'projects' => 'App\Models\Project',
                //     'content' => 'App\Models\Content',
                //     'experience' => 'App\Models\Experience',
                //     ...
                // ]

            // Further instructions in each of the classes will modify the auto populated index page





