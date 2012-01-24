<ol>
<? foreach ($d as $v) { ?>
  <li>
    <?= $v['text'].'<p style="color: #007BBF"><b>Ответы:</b> '.implode(', ', $v['answer']).'</p>'.
    ($v['passed'] ?
    '<div style="color:#2EBF00"><b>[пройден]</b></div>' :
    '<div style="color:#DF1000"><b>[провален]</b></div>') ?>
  </li>
<? } ?>
</ol>