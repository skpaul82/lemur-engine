<?php

/**
 * Combines SQL and its bindings
 *
 * @param \Eloquent $query
 * @return string
 */
function getEloquentSqlWithBindings($query)
{
    return vsprintf(str_replace('?', '%s', $query->toSql()), collect($query->getBindings())->map(function ($binding) {
        $binding = addslashes($binding);
        return is_numeric($binding) ? $binding : "'{$binding}'";
    })->toArray());
}
