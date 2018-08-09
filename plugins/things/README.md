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

USAGE
=====

This plugin is accessed by setting `"things"` as the Command in the [REST](https://restfulapi.net) URI. There are a number of other aspects to the URI that will be explained:

    {GET|POST|PUT|DELETE} http[s]://{SERVER URL}/{json|xml|xsd}/things/[INTEGER RESOURCE IDS CSV|STRING RESOURCE KEYS][?{show_details|show_parents|data_only|SEARCH PARAMETERS -DISCUSSED BELOW|PUT/POST PARAMETERS -DISCUSSED BELOW}]

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

SEARCH PARAMETERS
-----------------
**Locality Radius Searches**
All three of these must be used together. If you specify them, then any thing resources that have a long/lat that falls within the radius will be returned.
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
    
- `search_description=`

    *String.* This is an arbitrary string value that searches the description field.

- `search_tag2=` - `search_tag9=`

    *String.* This is an arbitrary string value that can be applied to any of the tags.

SPECIAL FORMATS OF RESPONSE DATA
--------------------------------
**Resource Response Format Query Parameters**
You can ask that data be returned in integers (individual resource IDs), as opposed to ID records, or even as a single integer, which specifies the number of resources that would be returned for the given search. In either case, the responses are wrapped in the requested data format (JSON or XML).

**NOTE:** These can only be used for GET method.

- `search_ids_only`
    
    *No Value Required -Just Add the Query.* If you add this, then the response will be arrays of integers, which will represent resource IDs (which can be used in resource specifiers in subsequent calls).

- `search_count_only`
    
    *No Value Required -Just Add the Query.* If you add this, then the response will be a single integer. It will be the number of resources that would be returned by the specified search.

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

SPECIAL DATA RESPONSE FORMAT
----------------------------

With things, it is possible to request that the data for one single resource be returned "raw" (not enclosed in either JSON or XML). You do this by adding the `"data_only"` query argument (does not need a value) to the access. If you have more than one resource selected, then only the first resource's data will be returned.

If you specify `"data_only"`, then the response format (`json` or `xml`) will be ignored (but you still need to specify it), and the data will be returned directly as raw Base64 data. This data comes from the thing's payload field.

POST AND PUT PARAMETERS
-----------------------
There are parameters that can be used to set data when creating new places (POST), or editing existing ones (PUT):

- `name=`

    *String.* This is a simple resource name.

- `lang=`

    *String.* This is the language identifier.

- `longitude=`

    *Floating-Point Decimal Value.* This is the longitude of the resource, in degrees.
    
- `latitude=`
    
    *Floating-Point Decimal Value.* This is the latitude of the resource, in degrees.

- `fuzz_factor=`
    
    *Floating-Point Decimal Value.* This is the value, in kilometers, of the location obfuscation. Setting this to zero (empty) will turn location obfuscation off.

- `can_see_through_the_fuzz=`
    
    *Integer.* This is a security token that is allowed to see the "true" location of a location-obfuscated place.
    
- `read_token=`
    
    *Integer.* This is a security token that is allowed to see this resource. You are allowed to set this to 0 (everyone can see) or 1 (all logged-in users can see, but only logged-in users).
    
- `write_token=`
    
    *Integer.* This is a security token that is allowed to modify this resource. You can set this to 1 (all logged-in users can modify), but not 0.
    
- `description=`

    *String.* This is an arbitrary string value that searches the description field.

- `tag2=` - `tag9=`

    *String.* This is an arbitrary string value that can be applied to any of the tags.

PAYLOAD DATA
------------

Things are meant to store data, and that data is usually supplied in the payload.

Like all BAOBAB resources, payload data is uploaded as multipart-form-encoded (POST), or direct inline (PUT) data.

It is downloaded in the JSON or XML response for a `"show_details"` query. It is always [Base64-encoded](https://en.wikipedia.org/wiki/Base64), or directly, if you use `"data_only"`.

It is not advisable to store items over about 5MB in the payload (although it is probably possible). In these cases, it is usually a better idea to store the data on a dedicated site, and supply a resource URI for it as the payload.

ENCRYPTION
----------

BAOBAB does not encrypt stored data. However, there is no reason that the data cannot be encrypted separately and stored in encrypted form. Access to the data is controlled by the standard security token system.

LICENSE
=======

![Little Green Viper Software Development LLC](images/viper.png)
© Copyright 2018, [Little Green Viper Software Development LLC](https://littlegreenviper.com).
This code is ENTIRELY proprietary and not to be reused, unless under specific, written license from [Little Green Viper Software Development LLC](https://littlegreenviper.com).
