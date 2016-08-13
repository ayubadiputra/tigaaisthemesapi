<?php

/* Get token access */
Routes::get( "/aisjson/oauth/callback/", "Authorization@get" );

/* Get API data */
Routes::get( "/aisjson/search/{post_type:all}/{limit:num}/{year:num}/{month:num}/{day:num}", "Data@getSearchData" );
Routes::get( "/aisjson/latest/{post_type:all}/{limit:num}/{year:num}/{month:num}/{day:num}", "Data@getLatestData" );
Routes::get( "/aisjson/single/{post_type:all}/{param_type:all}/{compare:all}/{param:all}", "Data@getSingleDataByParam" );
Routes::get( "/aisjson/category/{post_type:all}", "Data@getDataCategory" );
Routes::get( "/aisjson/categoryPost/{post_type:all}/{limit:num}/{tax:all}/{type:all}/{category:all}", "Data@getDataByCategory" );

/* Post API data */
Routes::post( "/custom-form", "Services@proceed" );