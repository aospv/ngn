<?php

if (!Auth::get('id')) unset($this->fields['price2']);
