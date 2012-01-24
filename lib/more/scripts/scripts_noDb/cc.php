<?php

SFLM::clearJsCssCache();
NgnCache::clean();
FancyUploadTemp::cleanup();
print '<div id="result">success</div>';