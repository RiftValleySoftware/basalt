\page users-plugin USERS STANDARD PLUGIN

![USERS PLUGIN](images/BASALT-PLUGIN.png)

BASALT USERS PLUGIN
===================

Part of the BASALT Extension Layer, Which is Part of the BAOBAB Server, which is part of the Rift Valley Platform
-----------------------------------------------------------------------------------------------------------------
![BAOBAB Server and The Rift Valley Platform](images/BothLogos.png)

INTRODUCTION
============

The Users Plugin is a basic, `GET`-only \ref BASALT Plugin; part of the "standard" set.

You use this plugin to obtain listings/overall details of users and logins in the BAOBAB server.

It is not a comprehensive user/login plugin. It's meant to give "quick overviews" of these types of records.

USAGE
=====

This plugin is accessed by setting `"users"` as the Command in the GET URI. There are a number of other aspects to the URI that will be explained:

    {GET} http[s]://{SERVER URL}/{json|xml|xsd}/users/[{users|logins}/[{[INTEGER USER IDS CSV]}|[{login_ids}/{[STRING LOGIN IDS CSV]}][?][{show_details|login_user|}]]

GET-ONLY
--------

You can only access this plugin via `GET` usage of other methods will result in an error.

MUST BE LOGGED IN
-----------------

This plugin will return an empty set, unless the user is logged in, and has the requisite security tokens to view (writing is not handled by this plugin, so write permission is not required) various user and login items.

SERVER URL
----------

    http[s]://{SERVER URL}/
    
This is the base URL to the BAOBAB executable (which will eventually end up calling \ref `entrypoint.php`).

RESPONSE TYPE
-------------

This is the requested response type. It is required, and will generally be either `"json"` or `"xml"`, depending on what type of response you want.

It can also be `"xsd"`, but be aware that specifying this will ignore all other parameters, and simply return the XML for the plugin's schema.

SPECIAL CALLS
-------------

There are two "special" calls that can be made with this plugin:

    {GET} http[s]://{SERVER URL}/{json|xml}/users/
    
Simply calling `users`, specifying only JSON or XML as the response type. Any query parameters are ignored.
    
Calling this will return a simple listing of the two top-level commands (`users` and `logins`); either in JSON or XML.
    
    {GET} http[s]://{SERVER URL}/xsd/users/
    
Calling `users`, specifying `xsd` as the response type.

Calling this will return XML, which will be the XML validation schema for the plugin. It's a comprehensive schema that covers all response types.
    
TYPES OF RECORDS
----------------

The plugin will return information (assuming permission) about two types of records:

- Users (Data database -The base class is CO_User_Collection)

    These are collection objects that describe individual users/persons in the database.
    
- Logins (Security Database -The base class is CO_Security_Login)

    These are the actual security login objects visible to the currently logged-in user.

With the users, it is also possible to get information about associated logins.

LICENSE
=======

![Little Green Viper Software Development LLC](images/viper.png)
© Copyright 2018, [Little Green Viper Software Development LLC](https://littlegreenviper.com).
This code is ENTIRELY proprietary and not to be reused, unless under specific, written license from [Little Green Viper Software Development LLC](https://littlegreenviper.com).
