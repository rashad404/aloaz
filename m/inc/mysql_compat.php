<?php
/**
 * MySQL Compatibility Layer for PHP 7+
 * Maps deprecated mysql_* functions to mysqli_*
 */

// Disable mysqli exception mode to match old mysql_* behavior (return false on error)
mysqli_report(MYSQLI_REPORT_OFF);

if (!function_exists('mysql_connect')) {

    $GLOBALS['__mysql_compat_link'] = null;

    function mysql_connect($server, $username, $password) {
        $GLOBALS['__mysql_compat_link'] = @mysqli_connect($server, $username, $password);
        // Disable ONLY_FULL_GROUP_BY for legacy code compatibility
        if ($GLOBALS['__mysql_compat_link']) {
            @mysqli_query($GLOBALS['__mysql_compat_link'], "SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");
        }
        return $GLOBALS['__mysql_compat_link'];
    }

    function mysql_select_db($database, $link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return mysqli_select_db($link, $database);
    }

    function mysql_set_charset($charset, $link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return mysqli_set_charset($link, $charset);
    }

    function mysql_query($query, $link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return mysqli_query($link, $query);
    }

    function mysql_fetch_array($result, $type = MYSQLI_BOTH) {
        if (!$result) return null;
        return mysqli_fetch_array($result, $type);
    }

    function mysql_fetch_assoc($result) {
        if (!$result) return null;
        return mysqli_fetch_assoc($result);
    }

    function mysql_fetch_object($result) {
        if (!$result) return null;
        return mysqli_fetch_object($result);
    }

    function mysql_fetch_row($result) {
        if (!$result) return null;
        return mysqli_fetch_row($result);
    }

    function mysql_num_rows($result) {
        if (!$result) return 0;
        return mysqli_num_rows($result);
    }

    function mysql_affected_rows($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return mysqli_affected_rows($link);
    }

    function mysql_insert_id($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return mysqli_insert_id($link);
    }

    function mysql_error($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return $link ? mysqli_error($link) : '';
    }

    function mysql_errno($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return $link ? mysqli_errno($link) : 0;
    }

    function mysql_escape_string($string) {
        return mysqli_real_escape_string($GLOBALS['__mysql_compat_link'], $string);
    }

    function mysql_real_escape_string($string, $link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return mysqli_real_escape_string($link, $string);
    }

    function mysql_close($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return mysqli_close($link);
    }

    function mysql_free_result($result) {
        return mysqli_free_result($result);
    }

    function mysql_data_seek($result, $offset) {
        return mysqli_data_seek($result, $offset);
    }

    function mysql_result($result, $row, $field = 0) {
        mysqli_data_seek($result, $row);
        $datarow = mysqli_fetch_array($result);
        return $datarow[$field];
    }

    function mysql_ping($link = null) {
        $link = $link ?: $GLOBALS['__mysql_compat_link'];
        return mysqli_ping($link);
    }
}
?>
