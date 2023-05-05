<?php

// and or???

// split
    // action
    // array
    // Individual value

// int
    // 1
    // 1,2,3,4,5,6 // default in
    // 1,2,3,4,5,6::notIn
    // 1,2,3,4,5,6::in
    // 1,2::bt
    // 1::e
    // 1::gt
    // 1::gte
    // 1::lt
    // 1::lte
// float
    // 1.2
    // 1.3,2.5,3.6,4.4 // default in
    // 1.3,2.5,3.6,4.4::notIn
    // 1.3,2.5,3.6,4.4::in
    // 1.2,2.3::bt
    // 1.3::e
    // 1.3::gt
    // 1.3::gte
    // 1.3::lt
    // 1.3::lte
// date
    // 1/1/23
    // 2023-01-01 00:00:00
    // 1/1/23,2/1/23 // default bt
    // 1/1/23,2/1/23::bt
    // today,yesterday::bt
    // 1/1/23::e
    // 1/1/23::gt
    // 1/1/23::gte
    // 1/1/23::lt
    // 1/1/23::lte
    // ability to process date string (today), date formats, date and time, dateTime number, year
// string
    // sam // default like
    // sam::e
    // sam,sammy // default or like
    // sam,sammy::and like ??? ???
    // sam::like
// json
    // ? https://laravel.com/docs/10.x/queries#json-where-clauses
    // v1
        // get
            // ???
            // team.users.lead.lead_id,123::e
            // team.users.lead.lead_id,123::>
            // team.users.lead.lead_id,123::>=
            // team.users.lead.lead_id,123::<
            // team.users.lead.lead_id,123::<=
        // post, put, patch -> all or nothing
// includes
    // ? https://laravel.com/docs/8.x/eloquent-relationships#eager-loading-specific-columns
        // ! When using this feature, you should always include the id column and any relevant foreign key columns in the list of columns you wish to retrieve.
        // Book::with('author:id,name,book_id')->get();
        // author:id,name,book_id
    // ? https://laravel.com/docs/8.x/eloquent-relationships#eager-loading-multiple-relationships // ***
        // Book::with(['author', 'publisher'])->get();
        // author,posts
        // author:id,name,book_id::posts:id,title
        // author:id,name,book_id::posts
    // ? https://laravel.com/docs/8.x/eloquent-relationships#nested-eager-loading // ***
        // Book::with('author.contacts')->get();
        // posts.author.contacts
        // author,posts.author.contacts
        // author.contacts,posts.author.contacts
        // ???
        // author:id,name,book_id.contacts::posts.author:id,name.contacts:id,name
        // author:id,name,book_id::posts:id,title
        // author:id,name,book_id::posts
    // v2
        // ? https://laravel.com/docs/8.x/eloquent-relationships#constraining-eager-loads ???
        // User::with(['posts' => function ($query) {
        //     $query->where('title', 'like', '%code%');
        // }])->get();
        // $users = User::with(['posts' => function ($query) {
        //     $query->orderBy('created_at', 'desc');
        // }])->get();
        // author||created_at,title|desc::posts:id,title // ???
// class methods
    // fullName
    // fullName,fullAddress
// method responses // ?? v2
    // resource?method=methodName
    // resource?method=methodName::data
    // bireports?method=insidesales::2023-01-01,2023-03-31
// order by (ASC|DESC)
    // id
    // id,name
    // id::desc,name::asc
// select
    // id
    // id,name