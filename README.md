[]: # \page BASALT BASALT

![BASALT](images/BASALT.png)

BASALT
======
Part of the Rift Valley Platform
--------------------------------
![Rift Valley Platform](images/RVPLogo.png)

INTRODUCTION
============

SUBPROJECTS
===========
BASALT is the extension framework of the Rift Valley Platform. It implements the REST API, as well as a plugin extension mechanism.

![BASALT Diagram](images/BASALTLayers.png)

\ref ANDISOL is the "public face" of the lowest levels of the Rift Valley Platform. It encapsulates the model (database or server connections), and provides an object-based, functional model to the implementation layers.

\ref COBRA is the security administration toolbox for the Rift Valley Platform. It is only available to logged-in users that are "manager" users; capable of editing other users.

\ref CHAMELEON is the "First Layer Abstraction" from the data storage and retrieval. It implements a few higher-level capabilities, such as collections, users and places.

\ref BADGER is the "First Layer Connection" to the data storage subsystem. It uses [PHP PDO](http://php.net/manual/en/book.pdo.php) to abstract from the databases, and provide SQL-injection protection through the use of [PHP PDO Prepared Statements](http://php.net/manual/en/pdo.prepared-statements.php).

LICENSE
=======

![Little Green Viper Software Development LLC](images/viper.png)
© Copyright 2018, [Little Green Viper Software Development LLC](https://littlegreenviper.com).
This code is ENTIRELY proprietary and not to be reused, unless under specific, written license from [Little Green Viper Software Development LLC](https://littlegreenviper.com).
