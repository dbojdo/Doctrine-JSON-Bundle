<?php

// Backward compatibility with DBAL 2.x
if(!class_exists(\Doctrine\DBAL\Exception::class)) {
    class_alias(\Doctrine\DBAL\DBALException::class, \Doctrine\DBAL\Exception::class);
}
