<div class="apeform">
<?php 

print O::get('Form', new Fields(array(
  array(
    'title' => 'bbbbb2b',
    'type' => 'headerToggle'
  ),
  array(
    'title' => 'sdfsdf',
  ),
  array(
    'title' => 'asd',
    'name' => 'dddddddddd',
    'type' => 'fieldSet',
    'fields' => array(
      array(
        'title' => 'Fgreg',
        'name' => 'fff'
      ),
      array(
        'title' => 'Element 2',
        'name' => 'ffg',
        'type' => 'select',
        'options' => array(1)
      ),
    )
  ),
  array(
    'title' => 'sdfsdf',
  ),
  array(
    'title' => 'tgrg weff',
  ),
  
  
)))->html();

O::get('Form', new Fields(array(
  array(
    'title' => 'bbbbb2b',
    'type' => 'headerToggle'
  ),
  array(
    'title' => 'Simple title',
    'name' => 'title2',
  ),
  array(
    'title' => 'asd',
    'name' => 'dddddddddd',
    'type' => 'fieldSet',
    'fields' => array(
      array(
        'title' => 'Fgreg',
        'name' => 'fff'
      ),
      array(
        'title' => 'Element 2',
        'name' => 'ffg',
        'type' => 'select',
        'options' => array(1)
      ),
    )
  ),
  array(
    'title' => 'vwevewv',
    'type' => 'headerToggle'
  ),
  array(
    'title' => 'FSFS',
    'name' => 'titlef2',
  ),
  
)))->html();

?>
</div>

<script>
new Ngn.Form(document.getElement('form'));
</script>
