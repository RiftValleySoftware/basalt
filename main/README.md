\page rest-plugin-baseline BASELINE

![BASELINE PLUGIN](images/BASALT-PLUGIN.png)

BASALT BASELINE REST PLUGIN
===========================

Part of the BASALT Extension Layer, Which is Part of the BAOBAB Server, which is part of the Rift Valley Platform
-----------------------------------------------------------------------------------------------------------------
![BAOBAB Server and The Rift Valley Platform](images/BothLogos.png)

INTRODUCTION
============

The Baseline Plugin is a basic \ref BASALT [REST](https://restfulapi.net) Plugin; part of the "standard" set.

You use this plugin to view and manage security tokens, list plugins, and do "non-denominational" searches on the BAOBAB server.

USAGE
=====

This plugin is accessed by setting `"baseline"` as the Command in the [REST](https://restfulapi.net) URI. There are a number of other aspects to the URI that will be explained:

    {GET|POST} http[s]://{SERVER URL}/{json|xml|xsd}/baseline/[{tokens|serverinfo|search[/?[search_radius= & search_longitude= & search_latitude=]|search_name=|search_tag0=|...|search_tag9=]|handlers/{IDS OF RESOURCES}]

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

The plugin will return the following information:

- plugins

    A simple list of available plugins (usually "baseline", "people", "places", and "things"). The response will be an array of string.
    
- tokens

    These are the actual security tokens visible to the currently logged-in user. The response will be an array of integers.
    
- search/handler results
    
    These are resource IDs of data objects that fit within the search criteria given (and are visible to the current user). These will be delivered in an assocative array of integer arrays. The top-level keys will indicate which plugin to use to examine the search results in the contained array of integers.

- visibility results

    These will be records with an ID (either a token or record ID), and lists of associated login IDs.
    
GET CALLS
---------

No Command
----------

    {GET} http[s]://{SERVER URL}/{json|xml|xsd}/baseline
    
When called with `"xml"` or `"json"` as response types, this will simply return a list of the available plugins (usually "baseline," "people," "places," and "things").

When called with `"xsd"` as the response type, this will return the XML XSD document for this plugin. The schema is comprehensive, specifying all possible response types.

tokens
------

    {GET} http[s]://{SERVER URL}/{json|xml}/baseline/tokens

This will return a simple array of integers, listing the security tokens available to the current logged-in user. Calling this when not logged in will result in a 403 (FORBIDDEN) error.

The login ID of the current user is always the first element of the array.

search
------

    {GET} http[s]://{SERVER URL}/{json|xml}/baseline/search[?[search_radius= & search_longitude= & search_latitude=]|search_name=|search_tag0=|...|search_tag9=]

This allows for specialization, using the URI search specification modifiers. Calling it without these modifiers will return the entire visible database.

The response is an associative array (the keys are the plugin names), containing simple arrays of integers. Each integer is the resource ID of a resource visible to the current user.

The available search specifiers are:

- `search_name=`

- `search_tag0=`, `search_tag1=`, `search_tag2=`, `search_tag3=`, `search_tag4=`, `search_tag5=`, `search_tag6=`, `search_tag7=`, `search_tag8=`, `search_tag9=`

These are all text specifiers, and should have the `=` followed by text. Leaving this blank specifies that you want only records that have that field blank. If you use more than one of the `search_tag` specifiers, they act in an AND fashion, with each one narrowing the search.

It should be noted that the various tags have different default functions, based upon the plugin. For example, in the `people` plugin, the first seven tags are used for names and connection to the user's login, and in the `places` plugin, the first eight tags are used for address information. In the `things` plugin, `tag0` is used to hold the key to look up the resource.

- `search_radius=`
- `search_longitude=`
- `search_latitude=`

These must all be specified together in order for location searches to work. This can be used in conjunction with the above string searches.

Every resource type in the BAOBAB server can (but is not required to) have a location, denoted by a long/lat pair. If there is a location, it can be found, using this search. Resources without a long/lat pair will not be returned in location/radius searches.

These should all be followed by floating point numbers. `search_radius` is the *radius* (not diameter) of a circle, with the location specified by the `search_longitude` and `search_latitude` query arguments, which are a long/lat location (in degrees).

serverinfo
----------

    {GET} http[s]://{SERVER URL}/{json|xml}/baseline/serverinfo

This will return a structure, containing various server settings and information, such as versions and enabled features.

It is only accessible if you are logged in with the "God" admin.

handlers
--------

    {GET} http[s]://{SERVER URL}/{json|xml}/baseline/handlers/{LIST OF INTEGER IDS}

This command returns the ids provided, sorted into "handlers" (the plugins that handle those resources), in the same format as the `search` command. this is a good way to figure out how to interpret a resource ID.

visibility
----------

    {GET} http[s]://{SERVER URL}/{json|xml}/baseline/visibility/[{SINGLE INTEGER}|token/{SINGLE INTEGER}]

This command allows you to get the login IDs that can see (and write) individual records (`SIMPLE INTEGER`), or that have a security token (`token/INTEGER`). You can only test one record or token at a time. Note that if you do not have access to some logins, their IDs will not be included in the response; meaning that the response may be incomplete. This is only available if logged in, and you must have the token, or have at least read access to the record indicated by the ID.

POST CALLS
----------

There is only one POST call allowed: `"tokens"`. This must be called when logged in as at least a manager.

Calling this with the `"tokens"` resource identifier will create one single new token, and will return it in the response. It will also add it to the current logged-in user login.

    {POST} http[s]://{SERVER URL}/{json|xml}/baseline/tokens

LICENSE
=======

![Little Green Viper Software Development LLC](images/viper.png)
© Copyright 2018, [Little Green Viper Software Development LLC](https://littlegreenviper.com).
This code is ENTIRELY proprietary and not to be reused, unless under specific, written license from [Little Green Viper Software Development LLC](https://littlegreenviper.com).
