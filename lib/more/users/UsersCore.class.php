<?php

class UsersCore {

  static public function save($pageId, $pageTitle, $pathData) {
    if (!($userId = Auth::get('id'))) return;
    db()->query("REPLACE INTO users_pages SET
      userId=?d, pageId=?d, url=?, title=?, path=?, dateCreate=?", $userId, $pageId, 
      $_SERVER['REQUEST_URI'], $pageTitle, Tt::enumDddd($pathData, '$title', ' / '), dbCurTime());
  }
  
  static public function getOnline() {
    return db()->query("
    SELECT
      users.login,
      users_pages.userId,
      users_pages.url,
      users_pages.title,
      users_pages.path
    FROM users_pages
    INNER JOIN users ON users_pages.userId=users.id
    WHERE users_pages.dateCreate > ?", date('Y-m-d H:i:s', time() - 60 * 1));
  }
  
  
  static public function sendLostPass($email) {
    if (($user = DbModelCore::get('users', $email, 'email')) === false) return false;
    return O::get('SendEmail')->send(
      $user['email'],
      'Восстановление пароля',
      'Ваш пароль: '.$user['passClear']
    );
  }

  static public function extendImageData(array &$users) {
    foreach ($users as &$v)
      $v += UsersCore::getImageData($v['id']);
  }
  
  static public function generateNames() {
    set_time_limit_q(0);
    

    foreach (db()->query("SELECT id, login FROM users WHERE name='' AND id>0") as $v) {
      try {
        $new = Misc::domain($v['login']);
      } catch (Exception $e) {
        
      }
      //print $new."<br>";
      db()->query('UPDATE users SET name=? WHERE id=?d', $new, $v['id']);
    }

    foreach (db()->query("SELECT id, login FROM users
    WHERE id>0 AND name=''
    ORDER BY id") as $v) {
      try {
        $_name = $name = Misc::domain($v['login']);
      } catch (NgnException $e) {
        try {
          $_name = $name = Misc::domain('a'.$v['login']);
        } catch (NgnException $e) {
          print '<p>Error while renerating names: '.$e->getMessage().'</p>';
          continue;
        }
      }
      $n = 1;
      while (db()->query('SELECT id FROM users WHERE name=?', $name)) {
        $name = $_name.$n;
        $n++;
        if ($n == 100)
          throw new NgnException('Limit of attempts to generate name is 100. login="'.$v['login'].'". last name="'.$name.'"');
      }
      db()->query('UPDATE users SET name=? WHERE id=?d', $name, $v['id']);
    }
  }
  
  // ------- html -----------
  
  static function avatarImg($userId, $login) {
    $v = UsersCore::getImageData($userId);
    return self::_avatarImg($userId, $login, empty($v['sm_image']) ? null : $v['sm_image']);
  }
  
  static $avatarCachePath = 'user-cache';
  
  static function avatarImgResized($userId, $login, $w, $h) {
    if (!in_array(array($w, $h), Config::getVar('avatarSizes')))
      throw new NgnException("Size {$w}x{$h} not allowed");
    $path = self::imagePath($userId);
    $file = UPLOAD_PATH.'/'.$path;
    if (!file_exists($file)) return self::_avatarImg($userId, $login);
    $resizedPath = self::$avatarCachePath.'/'.$w.'x'.$h.'/'.$userId.'.jpg';
    if (!file_exists(UPLOAD_PATH.'/'.$resizedPath)) {
      Dir::make(UPLOAD_PATH.'/'.dirname($resizedPath));
      O::get('Image')->resizeAndSave(
        UPLOAD_PATH.'/'.$path, UPLOAD_PATH.'/'.$resizedPath, $w, $h);
    }
    return self::_avatarImg($userId, $login, UPLOAD_DIR.'/'.$resizedPath);
  }
  
  static public function cleanAvatarCache($userId) {
    foreach (Config::getVar('avatarSizes') as $v)
      File::delete(UPLOAD_PATH.'/'.self::$avatarCachePath.'/'.$v[0].'x'.$v[1].'/'.$userId.'.jpg');
  }
  
  static function _avatarImg($userId, $login, $path = null) {
    return '<img src="/'.(
      !empty($path) ? $path :
      (file_exists(WEBROOT_PATH.'/m/img/no-avatar.gif') ?
        'm/img/no-avatar.gif' :
        'i/img/no-avatar.gif'
      )).'" title="'.$login.'" />';
  }
  
  static function avatar($userId, $login, $class = '') {
    $v = UsersCore::getImageData($userId);
    return '<div class="avatar hover'.(!empty($v['sm_image']) ? '' : ' noAvatar').
      ($class ? ' '.$class : '').'">'.
      '<a href="'.Tt::getUserPath($userId).'">'.
      self::_avatarImg($userId, $login, empty($v['sm_image']) ? null : $v['sm_image']).
      '</a></div>';
  }

  static function avatarAndLogin($userId, $login) {
    return self::avatar($userId, $login).'<h2>'.Tt::getUserTag($userId, $login).'</h2>';
  }
  
  static protected function imagePath($userId) {
    return 'user/'.$userId.'.jpg';
  }
  
  static public function getImageData($userId) {
    $path = self::imagePath($userId);
    if (is_file(UPLOAD_PATH.'/'.$path)) {
      $path = UPLOAD_DIR.'/'.$path;
      return array(
        'image' => $path, 
        'sm_image' => Misc::getFilePrefexedPath($path, 'sm_', 'jpg'), 
        'md_image' => Misc::getFilePrefexedPath($path, 'md_', 'jpg')
      );
    }
    return array();
  }
  
  static public function getRoles() {
    $roles = array(
      'name' => '',
      'title' => 'Пользователь'
    );
    if (($_roles = Config::getVarVar('role', 'roles', true)) !== false) $roles += $_roles;
    return $roles;
  }

}