<?php

q("delete from `notify_subscribe_types` WHERE type='change' or type='update'");
q("delete from `notify_subscribe_items` WHERE type='change' or type='update'");
