<?php

q("ALTER TABLE `storeCart` ADD PRIMARY KEY (`sessionId`, `pageId`, `itemId`)");
