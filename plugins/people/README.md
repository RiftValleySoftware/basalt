\page rest-plugin-people PEOPLE

![PEOPLE PLUGIN](images/BASALT-PLUGIN.png)

BASALT PEOPLE REST PLUGIN
=========================

Part of the BASALT Extension Layer, Which is Part of the BAOBAB Server, which is part of the Rift Valley Platform
-----------------------------------------------------------------------------------------------------------------
![BAOBAB Server and The Rift Valley Platform](images/BothLogos.png)

INTRODUCTION
============

The People Plugin is a basic \ref BASALT [REST](https://restfulapi.net) Plugin; part of the "standard" set.

You use this plugin to view and manage users and logins on the BAOBAB server.

USAGE
=====

This plugin is accessed by setting `"people"` as the Command in the [REST](https://restfulapi.net) URI. There are a number of other aspects to the URI that will be explained:

    {GET|POST|PUT|DELETE} http[s]://{SERVER URL}/{json|xml|xsd}/people/[{people|logins|personal_ids}/[{INTEGER USER IDS CSV}|[{login_ids/[STRING LOGIN IDS CSV]}]|{INTEGER PERSONAL IDS CSV}[/my_info][?{show_details|show_parents|login_user|PUT/POST PARAMETERS -DISCUSSED BELOW}]

MUST BE LOGGED IN
-----------------

This plugin will return an empty set, unless the user is logged in, and has the requisite security tokens to see and/or modify the resource.

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

- Users (Data Database -The base class is CO_User_Collection)

    These are collection objects that describe individual people/persons in the database. They may or may not be associated with a login (Security Database) record.
    
- Logins (Security Database -The base class is either CO_Cobra_Login or CO_Login_Manager)

    These are the actual security login objects visible to the currently logged-in user.

With the users, it is also possible to get information about associated logins.

SPECIAL CALLS
-------------

There are two "special" GET calls that can be made with this plugin:

    {GET} http[s]://{SERVER URL}/{json|xml}/people/
    
Simply calling `people`, specifying only JSON or XML as the response type. Any query parameters are ignored.
    
Calling this will return a simple listing of the two top-level commands (`people` and `logins`); either in JSON or XML. You will not see `"logins"` unless you are logged in.
    
    {GET} http[s]://{SERVER URL}/xsd/people/
    
Calling `people`, specifying `xsd` as the response type.

Calling this will return XML, which will be the XML validation schema for the plugin. It's a comprehensive schema that covers all response types.

GET CALLS
---------

people
------

    {GET} http[s]://{SERVER URL}/{json|xml}/people/people/[{[INTEGER USER IDS CSV]}|[{login_ids/[INTEGER LOGIN IDS CSV]|[STRING LOGIN IDS CSV]][?{show_details|show_parents|login_user}]
    
In this case, we are asking for user records (as opposed to login records). We have a number of choices as to how we can ask for these:

    {GET} http[s]://{SERVER URL}/{json|xml}/people/people[?][{show_details|show_parents|login_user}]

Calling `/people/people` with no extra path information.

In this case, we are asking for every user visible to the current login (if we are logged in as "God," then we will see every user in the system).

We can modify the query with a couple of query parameters:

- `show_details`

If this is specified (a value is not necessary, and will be ignored, if provided), then we will ask for "comprehensive" details on each user, which will include a full dump of any associated login record.

- `login_user`

This says don't return any people that don't have associated login IDs. It will also show each user's `"show_details"` response, with additional full information about each user's login (as long as your user has permission to read both the user and the login record).

For example:

    {GET} http[s]://example.com/entrypoint.php/json/people/people?login_user

Will return every user visible, that also has an associated login ID, in JSON. Each user will also have a "comprehensive" information dump, including the associated login.

    {GET} http[s]://{SERVER URL}/{json|xml}/people/people/{[INTEGER USER IDS CSV]}[?{show_details|show_parents|login_user}]

Calling `people/people/` with one or more integers in a CSV list. For example:

    {GET} http[s]://example.com/entrypoint.php/json/people/people/100,200,23,6000
    
Will show the summaries of the people with IDs of 100, 200, 23, and 6000; in that order in JSON.
    
You can also use the two query arguments mentioned in the previous example, or have just one ID.

    {GET} http[s]://example.com/entrypoint.php/XML/people/people/23?show_details
    
Will show you a comprehensive dump of the user with ID 23 in XML.

    {GET} http[s]://{SERVER URL}/{json|xml}/people/people/login_ids}/{[INTEGER LOGIN IDS CSV]}|{[STRING LOGIN IDS CSV]}][?show_details]

Calling `people/people/login_ids`, followed by a CSV list of either numbers (login record -not user- IDs), or strings (login record string login IDs).

In this variant, you are fetching user records by the login IDs of associated logins. For this reason, all returned user records will have associated logins.

Examples:

    {GET} http[s]://example.com/entrypoint.php/json/people/people/login_ids/10,20,567

Gets the summary for the people associated with login record IDs 10, 20 and 567 as JSON.

    {GET} http[s]://example.com/entrypoint.php/xml/people/people/login_ids/bob,Theodore,a71C3?show_details

Gets the detailed dumps for the three people associated with the logins accessed via `bob`, `Theodore` and `a71C3`, in XML.

**NOTE:** If the login is not associated with a user, or your login does not have permission to view both records, the login ID/login string will be ignored.

**NOTE:** You can have **EITHER** a CSV list of strings (login IDs) **OR** integers (login record IDs); but not a combination of both.

logins
------

We can also get login record information; which we do by appending `logins` to the `people` command, like so:

    {GET} http[s]://{SERVER URL}/{json|xml}/people/logins/[{[INTEGER LOGIN IDS CSV]}|{[STRING LOGIN IDS CSV]}][?show_details]

This URI is followed by a CSV list of numeric login record IDs or string login IDs, in exactly the same fashion as above. In this case, the returned data will be for login records, not user records.

As above, we can choose to show details.

Examples:

    {GET} http[s]://example.com/entrypoint.php/json/people/logins
    
Will display all the login records as JSON.

    {GET} http[s]://example.com/entrypoint.php/json/people/logins/10,20,567

Gets the summary for the logins (not users) associated with login record IDs 10, 20 and 567 as JSON.

    {GET} http[s]://example.com/entrypoint.php/xml/people/logins/bob,Theodore,a71C3?show_details

Gets the detailed dumps for the three logins associated with the logins accessed via `bob`, `Theodore` and `a71C3`, in XML.

    {GET} http[s]://example.com/entrypoint.php/xml/people/logins?show_details

Gives a comprehensive dump of all logins in XML.

**NOTE:** As above, you can have **EITHER** a CSV list of strings (login IDs) **OR** integers (login record IDs); but not a combination of both.

**NOTE:** With the `people/logins` call, you can get logins that have no associated user records, and with the `people/people` call, you can get users that have no associated login records (as long as you have not accessed the users by login ID).

personal_ids
------

We can also get personal token record information; which we do by appending `personal_ids` to the `people` command, like so:

    {GET} http[s]://{SERVER URL}/{json|xml}/people/personal_ids/[INTEGER PERSONAL IDS CSV]|my_info

Special Manager-Only Test Login Call
------------------------------------

    {GET} http[s]://example.com/entrypoint.php/{json|xml}/people/logins/<LOGIN ID STRING>?test
    
Can only be called by managers. This tests to see if a given login string is in use (Remember that they need to be unique to the system). It returns true, if it is (even if the manager is not authorized to see the login). The response does not contain the tested login (for security reasons).

SEARCH PARAMETERS
-----------------
**NOTE:** These searches are only applicable to GET. They will not be usable in PUT or DELETE calls. They are also only applicable to `people` objects (users, not logins).

**Locality Radius Searches**
All three of these must be used together. If you specify them, then any place resources that have a long/lat that falls within the radius will be returned.
Resources without long/lat will not be returned.

- `search_longitude=`

    *Floating-Point Decimal Value.* This is the longitude of the resource, in degrees.
    
- `search_latitude=`

    *Floating-Point Decimal Value.* This is the latitude of the resource, in degrees.
    
- `search_radius=`

    *Floating-Point Decimal Value.* This is a value for a radius (not diameter) circle around the given longitude and latitude. It is in Kilometers.
    If the resource is "fuzzy," it is possible that the long/lat shown by the resource (which is deliberately inaccurate) may be outside the radius, but the actual resource location is within the radius.
    
**String Searches**
These searches allow you to specify a simple case-insensitive string value for the indicated resource column. You can use SQL-style wildcards (%) in the strings. Not specifying a value, but specifying the parameter name and equals sign indicates that the indicated field MUST be empty. Not specifying a field at all indicates that the value of that field is not considered in the search.
These are actual string matches. They do not do an address lookup, and resources that don't have long/lat can be returned in these searches.

- `search_name=`

    *String.* This is a simple resource name.

- `search_surname=`

    *String.* This is the surname.

- `search_middle_name=`

    *String.* This is the middle name.

- `search_given_name=`

    *String.* This is the first ("given") name.

- `search_nickname=`

    *String.* This is the nickname.

- `search_prefix=`

    *String.* This is the prefix.

- `search_suffix=`

    *String.* This is the suffix.

- `search_tag7=`

    *String.* This is Tag 7.

- `search_tag8=`

    *String.* This is Tag 8.

- `search_tag8=`

    *String.* This is Tag 9.

PAGING OF RESPONSE DATA
-----------------------
**Paging Query Parameters**
These can be used for GET, PUT and DELETE.

It is possible to request that the data be returned in "pages," where you specify a "page size," and then a "requested page of results." This allows you to focus on only a part of a large dataset, or have the response sent back in manageable-size pieces.

Paging is another place we deviate from standard REST. In REST, you usually indicate pages in the resource request, but we specify paging via query parameters:

These apply to both normal resource response and ID-only resource response.

- `search_page_size=`

    *Integer.* This is the number of resources to send per page. If the page size is greater than the available resources for that page, then the number of resources returned for that page will be fewer than the specified page size.

- `search_page_number=`

    *Integer.* This is the 0-based index of the requested page. Only the resources for that single page will be returned. If the index is greater than the number of available pages, then nothing will be returned.

MISCELLANEOUS PARAMETERS
------------------------
These are additional parameters that you can use to specify various formats and information fields for resources.

- `writeable`

    *No Value Required -Just Add the Query.* If you add this, then only resources that can be modified by the current logged-in user will be returned. This will apply to GET, PUT and DELETE.

    **NOTE:** It goes without saying (but we're saying it anyway) that this will only be useful when logged in with an API Key.

- `show_details`

    *No Value Required -Just Add the Query.* If you add this, then resource records will be returned, showing as much information about the resources as possible (in GET method). Otherwise, you will receive an abbreviated response.

- `show_parents`

    *No Value Required -Just Add the Query.* If you add this, then resource records will be returned, showing as much information about the resources as possible (in GET method), and they will also list any "parent" records that have the given record as a "child." It should be noted that specifying this can add considerable overhead to the call; slowing it down significantly. It's really designed for "focused" resource information.

    **NOTE:** This only applies to user records. Logins have associated users, which are different from "parents."

POST CALLS
----------
You can create new users, logins, or both, if you are logged in as a Manager (not a regular user) or the "God" login. You do this by calling the basic `"people/people"` or `"people/logins"` commands with a POST method.

You can only create one resource (or pair) at a time with POST.

If you call the /people with a `"login_id="` value, containing a unique (on the server) login ID string, you will create a user/login pair. If you do not specify this, then you will create a simple, standalone user (with no associated login).

    {POST} http[s]://example.com/entrypoint.php/json/people/people

Will create a simple, standalone user with no associated login, and default values.

    {POST} http[s]://example.com/entrypoint.php/json/people/people?login_id=SomeRandomLoginString

Will create a simple, default user, but it will also have an associated login with a login ID string of `"SomeRandomLoginString"`.

You can also create standalone logins, by specifying the `"logins"` path component, and either appending the new login ID string to the component, or specifying it with a `"login_id="` query argument:

    {POST} http[s]://example.com/entrypoint.php/json/people/logins/SomeRandomLoginString
    {POST} http[s]://example.com/entrypoint.php/json/people/logins?login_id=SomeRandomLoginString

Will both create a simple, default login with a login ID string of `"SomeRandomLoginString"`.

PUT CALLS
---------
Use PUT to update existing resources. The modifications will be made to the entire found set of resources (so you are not just restricted to one at a time, like POST).

POST AND PUT QUERY PARAMETERS
-----------------------------
Instead of sending fully-formed JSON or XML data to the server, we use query parameters to specify new values for resource data. We use PUT to change existing resources, and POST to create new ones, and they can use the same query parameters to indicate new values for the resources.

In the case of PUT, it is possible to apply a common set of values to multiple resources, with invalid or non-existent resource field values being simply ignored (for example, a field may apply to some of the requested resources, but not all, so it is applied to the ones to which it applies, and is not applied to the resources to which it does not apply).

Specifying one of these fields as empty (nothing following the `=` sign), indicates that the field should be set to NULL, or cleared.

- `name=`

    *String.* This is a simple resource name. If supplied to a request that includes a login, the `"object_name"` column in both resources (assuming write permissions on both) will be set to the given value.

- `lang=`

    *String.* This is the language identifier.

- `password=`

    *String.* This is a new password for the user login. This will not be applied to standalone user objects (objects with no associated login).
    
- `longitude=`

    *Floating-Point Decimal Value.* This is the longitude of the resource, in degrees.
    
    **NOTE:** Login objects cannot have location information associated with them.
    
- `latitude=`

    *Floating-Point Decimal Value.* This is the latitude of the resource, in degrees.
    
    **NOTE:** Login objects cannot have location information associated with them.
    
- `fuzz_factor=`

    *Floating-Point Decimal Value.* This is a distance, in Kilometers, for [location obfuscation](https://en.wikipedia.org/wiki/Location_obfuscation) to be applied to the resource. This requires that the `longitude` and `latitude` resource fields be set.
    If set (setting this to blank, or 0 will clear location obfuscation), then a box (square) around the resource's actual location, double the value given to a side (i.e, a 5Km fuzz_factor will result in a 10Km X 10Km box), will be used as a "randomized dartboard" of locations that will be returned whenever the resource's location is queried (with the exception of the "can see through the fog" token).
    
    **NOTE:** Login objects cannot have location information associated with them.
    
- `can_see_through_the_fuzz=`

    *Decimal Integer.* This is a security token that specifies that additional fields of `raw_longitude` and `raw_latitude` will be sent to users that posess the token. These fields contain the actual location (unobfuscated). Users with write security clearance will be allowed to see the actual location.
   
    **NOTE:** Login objects cannot have location information associated with them.
     
- `read_token=`

    *Decimal Integer.* This is a new security token to be applied to the resource as its new read permission.

- `write_token=`

    *Decimal Integer.* This is a new security token to be applied to the resource as its new write permission.

- `tokens=`

    *String (A Comma-Separated List of Decimal Integer).* This is a set of new security tokens for the user. Only tokens available to the current manager will be set (excluding the ID of the manager login, itself). This is only applied to the login resource, so it will be ignored for user-only resources.

- `surname=`

    *String.* This is a family name (last name or surname) for the user. It is applied to the user object (not the login). Additionally, this is "tag1," for \ref rest-plugin-baseline "non-denominational" searches.

- `middle_name=`

    *String.* This is a middle name for the user. It is applied to the user object (not the login). Additionally, this is "tag2," for \ref rest-plugin-baseline "non-denominational" searches.

- `given_name=`

    *String.* This is a first ("given") name for the user. It is applied to the user object (not the login). Additionally, this is "tag3," for \ref rest-plugin-baseline "non-denominational" searches.

- `prefix=`

    *String.* This is a "prefix" (for example, "Mr.", "Dr.", "Representative", etc.) name for the user. It is applied to the user object (not the login). Additionally, this is "tag4," for \ref rest-plugin-baseline "non-denominational" searches.

- `suffix=`

    *String.* This is a "suffix" (for example, "Ph.D", "esq.", "LCSW", etc.) name for the user. It is applied to the user object (not the login). Additionally, this is "tag5," for \ref rest-plugin-baseline "non-denominational" searches.

- `nickname=`

    *String.* This is a "nickname" (for example, "Buddy", "Buffy", "Tommy", etc.) name for the user. It is applied to the user object (not the login). Additionally, this is "tag6," for \ref rest-plugin-baseline "non-denominational" searches.

- `tag7=`

    *String.* This is an arbitrary string value that can be applied to the user. It is not applied to the login.

- `tag8=`

    *String.* This is an arbitrary string value that can be applied to the user. It is not applied to the login.

- `tag9=`

    *String.* This is an arbitrary string value that can be applied to the user. It is not applied to the login.
    
DATA PAYLOAD
------------
In addition to the above query arguments, you can upload arbitrary base64-encoded data to the record, which will be held in its "payload" column. This can be fairly substantial, but it's a good idea to keep the size of these payloads down.

In POST, the payload should be sent as multipart-form, but in PUT, it is sent as simple inline.

When queried, the payload is returned in the `"show_details"` response. If it is a large payload, it can make the response quite large (and slow).

DELETE OPERATIONS
-----------------
Delete is quite simple. Just select one or more resources (either by direct selection, or by a search), and specify DELETE as the HTTP method.

Any records within that set that are writeable by the current login will be deleted, and a detailed report will be returned in the chosen format.

**NOTE:** You can specify that a login is deleted at the same time as the user by specifying the `?login_user` query argument on a `/people/people` resource specification.

![The Great Rift Valley Software Company](images/RiftValleySimpleCorpLogo.png)

LICENSE
=======

© Copyright 2018, The Great Rift Valley Software Company

LICENSE:

MIT License

Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy,
modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the
Software is furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

The Great Rift Valley Software Company: https://riftvalleysoftware.com
