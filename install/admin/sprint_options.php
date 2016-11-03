<?php

if (is_file($_SERVER["DOCUMENT_ROOT"] . "/local/modules/sprint.options/admin/sprint_options.php")) {
    /** @noinspection PhpIncludeInspection */
    require($_SERVER["DOCUMENT_ROOT"] . "/local/modules/sprint.options/admin/sprint_options.php");
} else {
    /** @noinspection PhpIncludeInspection */
    require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/sprint.options/admin/sprint_options.php");
}
