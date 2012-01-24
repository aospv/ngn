<?php

q('TRUNCATE TABLE comments_srt');
q('INSERT INTO comments_srt SELECT id, active, parentId, id2 FROM comments ORDER BY id DESC');