<? $d['path'] = $d['path'] ?: Tt::getPath() ?>
<div id="auth">
  <? /*if (Tt::getControllerPath('searcher', true)) { ?>
  <div class="item" style="float:right">
    <div id="searchBox">
      <input class="fld" name="s" id="fldSearch" value="text" />
      <a href="#" class="btn btn1" id="btnSearch">Искать</a>
    </div>
  </div>
  <? }*/ ?>
  <? if (!Auth::get('id')) { ?>
  <form action="<?= $d['path'] ?>" method="post" id="authForm">
   <div class="item"><input type="text" class="fld" name="authLogin" id="authLogin" value="login" /></div>
   <div class="item"><input type="password" class="fld" name="authPass" id="authPass" value="password" /></div>
   <!--
   <div class="item">
     <label for="myComputer">
     <input type="checkbox" name="expires" value="1" checked id="myComputer" />
       <small>чужой компьютер</small>
     </label>
   </div>
   -->
   <div class="item" style="position:relative">
     <a href="#" class="btn btnSubmit" id="btnLogin"><span>Войти</span></a>
     <?
     if ($d['errors']) {
       Tt::tpl('slideTips/auth', $d['errors']);
     }
     ?>
   </div>
   <div class="item">
     <a href="<?= Tt::getControllerPath('userReg') ?>" class="btn btn1" id="btnReg">
       <span>Регистрация</span></a>
   </div>
   <div class="item">
     <a href="<?= Tt::getControllerPath('userReg') ?>/lostpass">Забыли?</a>
   </div>
   <div class="clear"><!-- --></div>
   </form>
 <? } else { ?>
   <div id="personal">
     <div class="item iconsSet" id="myLogin"><a href="<?= Tt::getUserPath(Auth::get('id')) ?>" class="pseudoLink briefcase"><i></i><b><?= Auth::get('login') ?></b></a></div>
     <script type="text/javascript">new Ngn.site.top.briefcase.Menu(<?= Auth::get('id') ?>);</script>
     <? if (Misc::isGod()) { ?>
       <div class="item dgray iconsSet"><a href="./god" class="god"><i></i>Храм Господен</a></div>
     <? } elseif (Misc::isAdmin()) { ?>
       <div class="item dgray iconsSet"><a href="./admin" class="admin"><i></i>Панель управления</a></div>
     <? } ?>
     
     <?/*
     <div class="item iconsSet notext">
       <a href="<?= Tt::getControllerPath('userReg') ?>/editPass" class="settings" title="Регистрационные данные"><i></i></a>
       <? if (($path = Tt::getControllerPath('notify', true))) { ?>
         <a href="<?= $path ?>" class="notifySettings" title="Настройка уведомлений"><i></i></a>
       <? } ?>
     </div>
     */?>
     <? if (($pmPath = Tt::getControllerPath('privMsgs', true))) { ?>
     <div class="item iconsSet" style="position:relative">
       <a href="<?= $pmPath ?>" class="send<?= $d['privMsgs']['newMsgsCount'] ? '2' : 'Off' ?> gray" title="Приватные сообщения"><i></i>
         <?= $d['privMsgs']['newMsgsCount'] ? '<span>(<b id="pmCnt">'.$d['privMsgs']['newMsgsCount'].'</b>)</span>' : '' ?></a>
       <? Tt::tpl('slideTips/privMsgs') ?>
     </div>
     <? } ?>
     <div class="item gray"><a href="<?= $d['path'] ?>?logout=1">Выйти</a></div>
   </div>
 <? } ?>
</div>