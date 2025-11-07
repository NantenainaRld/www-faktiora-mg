<?php

function __($key, $params = [])
{
    return TranslationService::translate($key, $params);
}
