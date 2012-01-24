<?php

/**
 * Описать какие ключевые слова ({keyword}) используются 
 */
$tpl = array(
  'form' =>
    '<p><table>{input}</table></p>',
  'header' =>
    '<tr{rowClass}><th colspan="2">{header}</th></tr>',
  'input' =>
    '<tr><td>{label}</td><td>{input}{help}</td></tr>',
  'label' =>
    '{label}:',
  'error' =>
    '<strong class="error">{error}:</strong>',
  'help' =>
    '<br /><small>{help}</small>'
);
