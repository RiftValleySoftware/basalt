\page rest-plugin-things THINGS

![THINGS PLUGIN](images/BASALT-PLUGIN.png)

BASALT THINGS REST PLUGIN
=========================

Part of the BASALT Extension Layer, Which is Part of the BAOBAB Server, which is part of the Rift Valley Platform
-----------------------------------------------------------------------------------------------------------------
![BAOBAB Server and The Rift Valley Platform](images/BothLogos.png)

INTRODUCTION
============

The Things Plugin is a \ref BASALT [REST](https://restfulapi.net) Plugin; part of the "standard" set.

You use this plugin to access general data items in the BAOBAB server.

HOW THINGS WORK
===============
Things are general data items; from simple integers and strings, to massive media files (the recommended max is about 2MB, but we test with much larger files). Thing data is kept in the `payload` record column. It is sent and returned as Base64 data.

Like all BAOBAB resources, each thing resource has an integer ID, but they are also accessible by a string key. The key, like the ID, must be unique, but the key is something selected by the user; not the system.

Also, like all BAOBAB resources, you can assign a long/lat (with location obfuscation), as well as aggregate other resources (Child IDs).

Things have eight general-purpose "tags" available for use as indexing assists. The first two are used for the key and a description.

LICENSE
=======

![Little Green Viper Software Development LLC](images/viper.png)
© Copyright 2018, [Little Green Viper Software Development LLC](https://littlegreenviper.com).
This code is ENTIRELY proprietary and not to be reused, unless under specific, written license from [Little Green Viper Software Development LLC](https://littlegreenviper.com).
