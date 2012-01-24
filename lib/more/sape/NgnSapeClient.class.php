<?php

class NgnSapeClient extends SAPE_client {
  function _get_db_file() {
    return DATA_PATH.'/sape_links.db';
  }
}
