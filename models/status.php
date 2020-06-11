<?php
abstract class Status
{
    const __default = self::Admin;

    const Admin = 'ADMIN';
    const Permanent = 'PERMANENT';
    const Temporary = 'TEMPORARY';
    const Blocked = 'BLOCKED';
}
?>