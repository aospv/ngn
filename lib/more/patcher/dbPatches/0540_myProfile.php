<?php

db()->query('UPDATE pages SET controller=? WHERE controller=?',
  'myProfile', 'myUserData');
