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

    {GET|POST} http[s]://{SERVER URL}/{json|xml|xsd|csv}/baseline/[{version|tokens|serverinfo|search[/?[search_radius= & search_longitude= & search_latitude=]|search_name=|search_tag0=|...|search_tag9=]|handlers/{IDS OF RESOURCES}]

SERVER URL
----------

    http[s]://{SERVER URL}/
    
This is the base URL to the BAOBAB executable (which will eventually end up calling \ref `entrypoint.php`).

RESPONSE TYPE
-------------

This is the requested response type. It is required, and will generally be either `"json"` or `"xml"`, depending on what type of response you want.

It can also be `"xsd"`, but be aware that specifying this will ignore all other parameters, and simply return the XML for the plugin's schema.

`"csv"` is a special case. It can only be specified for the "God Mode" `"backup"` command.

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
    
- CSV Backup Data.

    This will only be returned by the "God Mode" `"backup"` command. It is a large string of CSV data that represents all the data in both databases of the server. It will be in a format suitable for the `"bulk-loader"` command.
    
GET CALLS
---------

No Command
----------

    {GET} http[s]://{SERVER URL}/{json|xml|xsd}/baseline
    
When called with `"xml"` or `"json"` as response types, this will simply return a list of the available plugins (usually "baseline," "people," "places," and "things").

When called with `"xsd"` as the response type, this will return the XML XSD document for this plugin. The schema is comprehensive, specifying all possible response types.

version
-------

    {GET} http[s]://{SERVER URL}/{json|xml}/baseline/version

This will return a string, with a simple [semantic version](https://semver.org) in it. The format will be:

    MAJOR . MINOR . PATCH . BUILD
    
The `"BUILD"` number will be a 4-digit (leading zeroes) number, that has the following format, to indicate the stage of the server:

- `0000`...`0999`
This is a "development" release. It should be considered unstable and incomplete.

- `1000`...`1999`
This is an "alpha" release. It should be considered stable, untested but complete.

- `2000`...`2999`
This is a "beta" release. It should be considered stable, tested, complete, but not yet approved for final release. This is usually when non-developers are asked to test the system.

- `3000` or greater
This is "release." It is considered tested, stable, and ready for release.

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

backup
------

    {GET} http[s]://{SERVER URL}/csv/baseline/backup
    
This command can ONLY be called if you are logged in as the "God" admin (main admin). Calling this will return a large CSV string, with the entire contents of the server (both databases). The format will be the same as the `"bulk-loader"` command.

POST CALLS
----------

tokens
------

The `"tokens"` command must be called when logged in as at least a manager.

Calling this with the `"tokens"` resource identifier will create one single new token, and will return it in the response. It will also add it to the current logged-in user login.

    {POST} http[s]://{SERVER URL}/{json|xml}/baseline/tokens
    
bulk-loader
-----------

The `"bulk-loader"` command requires that the login be the "God" admin, and that a variable in the configuration be set to allow upload. You must set the `$enable_bulk_upload` variable in the configuration file to `true` in order for this command to work.

    {POST} http[s]://{SERVER URL}/{json|xml}/baseline/bulk-loader (You must also attach a CSV file as the multipart-form variable in "payload")

This is a way to upload bulk data to a BAOBAB server. It requires that a CSV file be attached in the POST multipart-form variable (labeled as "payload"). The format of this CSV file is exactly the same as that returned from the `"backup"` command:

    id, api_key, login_id, access_class, last_access, read_security_id, write_security_id, object_name, access_class_context, owner, longitude, latitude, tag0, tag1, tag2, tag3, tag4, tag5, tag6, tag7, tag8, tag9, ids, payload
    
Depending on the class in the 'access_class' column, either the security or data databes will be affected by a given row. Note that columns correspond to BOTH databases, so some columns will be ignored.

The columns are (empty columns should be filled with 'NULL'):

- `id`

    This is the integer ID of the resource. It will likely be changed to one relevant to the server, but it must be valid for the CSV file (for example, if this resources is a "child" of another resource, or is a login associated with a user, then they must be the same ID). Upon successful upload, the ID translation will be returned in the response.
    
- `api_key`

    This is always NULL, and will be ignored, if supplied. It is a security database-only column.
    
- `login_id`

    This is a security database string login ID, and should be set to NULL for data database resources. If there is a duplicate login ID on the system already, this will have ' - copy' appended to it. If there is still a duplicate, you will get a 500 (Internal Server Error) response.
    
- `access_class`

    This is the class of the resource. This will also be used to determin which database the resource will be entered into.
    
- `last_access`

    This is the date of the last access, it is a string, in `YYYY-MM-DD HH:MM:SS` format.
    
- `read_security_id`

    This is the integer security token (in CSV ID terms) to be applied to the record's read permission. As noted in the `id` description, this will be translated by the upload process, and should be in the scope/context of the CSV file, not the destination server.
    
- `write_security_id`

    This is the integer security token (in CSV ID terms) to be applied to the record's write permission. As noted in the `id` description, this will be translated by the upload process, and should be in the scope/context of the CSV file, not the destination server.
    
- `object_name`

    This is a string, with the name of the object.
    
- `access_class_context`

    This is a string, containing the serialized `comtext` property of the class instance for this record.
    
- `owner`

    This is the integer ID of another record that is designated an "owner" of this record (data database only). As noted in the `id` description, this will be translated by the upload process, and should be in the scope/context of the CSV file, not the destination server.
    
- `longitude`

    This is the data database floating-point longitude of the record. It is in degrees longitude.
    
- `latitude`

    This is the data database floating-point latitude of the record. It is in degrees latitude.
    
- `tag0` - `tag9`

    These are string values for tags (data database only).
    
- `ids`

    This is a string, containing comma-delimited integers that represent security database tokens, to be applied to a login, as its "token pool." This is a security database column. As noted in the `id` description, this will be translated by the upload process, and should be in the scope/context of the CSV file, not the destination server.

Strings that contain spaces or other whitespace, commas (,) or double-quotes (") should be enclosed in double-quotes.

Double-quotes (") and single-quotes (') should be escaped by doubling ("" or '').

If you put 'NULL' in as a column value, that will be translated to NULL in the database.

LICENSE
=======

![The Great Rift Valley Software Company](images/RiftValleySimpleCorpLogo.png)
© Copyright 2018, The Great Rift Valley Software Company

LICENSE:

FOR OPEN-SOURCE (COMMERCIAL OR FREE):
This code is released as open source under the GNU Plublic License (GPL), Version 3.
You may use, modify or republish this code, as long as you do so under the terms of the GPL, which requires that you also
publish all modificanions, derivative products and license notices, along with this code.

UNDER SPECIAL LICENSE, DIRECTLY FROM LITTLE GREEN VIPER OR THE GREAT RIFT VALLEY SOFTWARE COMPANY:
It is NOT to be reused or combined into any application, nor is it to be redistributed, republished or sublicensed,
unless done so, specifically WITH SPECIFIC, WRITTEN PERMISSION from The Great Rift Valley Software Company LLC,
or The Great Rift Valley Software Company.

The Great Rift Valley Software Company: https://riftvalleysoftware.com
The Great Rift Valley Software Company: https://riftvalleysoftware.com
