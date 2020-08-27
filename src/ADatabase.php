<?php
namespace elzobrito;

abstract class ADatabase
{
    public abstract static function getDB($driver = null);
}
