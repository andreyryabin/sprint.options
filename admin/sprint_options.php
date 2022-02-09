<?php

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

try {
    include __DIR__ . '/includes/interface.php';
} catch (Throwable $e) {
    echo nl2br(
        sprintf(
            "[%s] %s (%s)\n%s\n",
            get_class($e),
            $e->getMessage(),
            $e->getCode(),
            $e->getTraceAsString()
        )
    );
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
