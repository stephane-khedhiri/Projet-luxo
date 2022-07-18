<?php

return [
  'GET'=> [
      '/' => 'Annoncement.home',
      '/home' => 'Annoncement.home',
      '/annoncemnt/:id'=> 'Annoncement.detail',
      '/category/:slug' => 'Annoncement.category',
      '/connect/' => 'User.connect',
      '/create/' => 'User.create',
      '/disconnect/' => 'User.disconnect',
      '/delect/' => 'Users.User.deleted',
      '/user/compte/'=> 'Users.User.edit',
      '/user/annoncement/' => 'Users.Annoncement.list',
      '/user/annoncement/create/' => 'Users.Annoncement.create',
      '/user/annoncement/edit/:id/' => 'Users.Annoncement.edit',
      '/user/annoncement/deleted/:id/' => 'Users.Annoncement.deleted',
      '/admin/' => 'Admins.Admin.connect',
      '/admin/statis' => 'Admins.Admin.statis',
      '/admin/statis/:id' => 'Admins.Admin.statis',
      '/admin/users/' => 'Admins.Admin.users',
      '/admin/user/:id'=> 'Admins.Admin.user',

  ],
  'POST' => [
      '/connect/' => 'User.connect',
      '/create/' => 'User.create',
      '/user/annoncement/create/' => 'Users.Annoncement.create',
      '/user/annoncement/edit/:id' => 'Users.Annoncement.edit',
      '/user/compte/'=> 'Users.User.edit',
      '/admin/' => 'Admins.Admin.connect',
  ]
];