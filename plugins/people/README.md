\page people-plugin PEOPLE STANDARD PLUGIN

![USERS PLUGIN](images/BASALT-PLUGIN.png)

BASALT PEOPLE PLUGIN
====================

Part of the BASALT Extension Layer, Which is Part of the BAOBAB Server, which is part of the Rift Valley Platform
-----------------------------------------------------------------------------------------------------------------
![BAOBAB Server and The Rift Valley Platform](images/BothLogos.png)

INTRODUCTION
============

The People Plugin is a basic, `GET`-only \ref BASALT Plugin; part of the "standard" set.

You use this plugin to obtain listings/overall details of users and logins in the BAOBAB server.

It is not a comprehensive user/login plugin. It's meant to give "quick overviews" of these types of records.

USAGE
=====

This plugin is accessed by setting `"people"` as the Command in the GET URI. There are a number of other aspects to the URI that will be explained:

    {GET} http[s]://{SERVER URL}/{json|xml|xsd}/people/[{people|logins}/[{[INTEGER USER IDS CSV]}|[{login_ids}/{[STRING LOGIN IDS CSV]}][?][{show_details|login_user}]]

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

TYPES OF RECORDS
----------------

The plugin will return information (assuming permission) about two types of records:

- Users (Data database -The base class is CO_User_Collection)

    These are collection objects that describe individual people/persons in the database.
    
- Logins (Security Database -The base class is CO_Security_Login)

    These are the actual security login objects visible to the currently logged-in user.

With the people, it is also possible to get information about associated logins.

SPECIAL CALLS
-------------

There are two "special" calls that can be made with this plugin:

    {GET} http[s]://{SERVER URL}/{json|xml}/people/
    
Simply calling `people`, specifying only JSON or XML as the response type. Any query parameters are ignored.
    
Calling this will return a simple listing of the two top-level commands (`people` and `logins`); either in JSON or XML.
    
    {GET} http[s]://{SERVER URL}/xsd/people/
    
Calling `people`, specifying `xsd` as the response type.

Calling this will return XML, which will be the XML validation schema for the plugin. It's a comprehensive schema that covers all response types.

REGULAR CALLS
-------------

people
-----

    {GET} http[s]://{SERVER URL}/{json|xml}/people/people/[{[INTEGER USER IDS CSV]}|[{login_ids}/{[INTEGER LOGIN IDS CSV]}|{[STRING LOGIN IDS CSV]}][?][{show_details|login_user}]
    
In this case, we are asking for user records (as opposed to login records). We have a number of choices as to how we can ask for these:

    {GET} http[s]://{SERVER URL}/{json|xml}/people/people[?][{show_details|login_user}]

Calling `/people/people` with no extra path information.

In this case, we are asking for every user visible to the current login (if we are logged in as "God," then we will see every user in the system).

We can modify the query with a couple of query parameters:

- `show_details`

If this is specified (a value is not necessary, and will be ignored, if provided), then we will ask for "comprehensive" details on each user, which will include a full dump of any associated login record.

- `login_user`

This says don't return any people that don't have associated login IDs.

For example:

    {GET} http[s]://example.com/entrypoint.php/json/people/people?login_user

Will return every user visible, that also has an associated login ID, in JSON.

    {GET} http[s]://{SERVER URL}/{json|xml}/people/people/{[INTEGER USER IDS CSV]}[?][{show_details|login_user}]

Calling `people/people/` with one or more integers in a CSV list. For example:

    {GET} http[s]://example.com/entrypoint.php/json/people/people/100,200,23,6000
    
Will show the summaries of the people with IDs of 100, 200, 23, and 6000; in that order in JSON.
    
You can also use the two query arguments mentioned in the previous example, or have just one ID.

    {GET} http[s]://example.com/entrypoint.php/XML/people/people/23?show_details
    
Will show you a comprehensive dump of the user with ID 23 in XML.

    {GET} http[s]://{SERVER URL}/{json|xml}/people/people/login_ids}/{[INTEGER LOGIN IDS CSV]}|{[STRING LOGIN IDS CSV]}][?{show_details}]

Calling `people/people/login_ids`, followed by a CSV list of either numbers (login record -not user- IDs), or strings (login record string login IDs). You can ask for details, but `login_user` is unnecessary.

In this variant, you are fetching user records by the login IDs of associated logins. For this reason, all returned records will have associated logins.

Examples:

    {GET} http[s]://example.com/entrypoint.php/json/people/people/login_ids/10,20,567

Gets the summary for the people associated with login record IDs 10, 20 and 567 as JSON.

    {GET} http[s]://example.com/entrypoint.php/xml/people/people/login_ids/bob,Theodore,a71C3?show_details

Gets the detailed dumps for the three people associated with the logins accessed via `bob`, `Theodore` and `a71C3`, in XML.

**NOTE:** If the login is not associated with a user, or your login does not have permission to view both records, the login ID/login string will be ignored.

logins
------

We can also get login record information; which we do by appending `logins` to the `people` command, like so:

    {GET} http[s]://{SERVER URL}/{json|xml}/people/logins/[{[INTEGER LOGIN IDS CSV]}|{[STRING LOGIN IDS CSV]}][?][{show_details}]

This URI is followed by a CSV list of numeric login record IDs or string login IDs, in exactly the same fashion as above. In this case, the returned data will be for login records, not user records.

As above, we can choose to show details.

Examples:

    {GET} http[s]://example.com/entrypoint.php/json/people/logins
    
Will display all the login records as JSON.

    {GET} http[s]://example.com/entrypoint.php/xml/people/logins?show_details

Gives a comprehensive dump of all logins in XML.

**NOTE:** With the `people/logins` call, you can get logins that have no associated user records.

LICENSE
=======

![Little Green Viper Software Development LLC](images/viper.png)
© Copyright 2018, [Little Green Viper Software Development LLC](https://littlegreenviper.com).
This code is ENTIRELY proprietary and not to be reused, unless under specific, written license from [Little Green Viper Software Development LLC](https://littlegreenviper.com).
