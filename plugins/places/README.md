\page rest-plugin-places PLACES

![PLACES PLUGIN](images/BASALT-PLUGIN.png)

BASALT PLACES REST PLUGIN
=========================

Part of the BASALT Extension Layer, Which is Part of the BAOBAB Server, which is part of the Rift Valley Platform
-----------------------------------------------------------------------------------------------------------------
![BAOBAB Server and The Rift Valley Platform](images/BothLogos.png)

INTRODUCTION
============

The Places Plugin is a basic \ref BASALT [REST](https://restfulapi.net) Plugin; part of the "standard" set.

You use this plugin to obtain listings/details of places (locations and addresses) in the BAOBAB server.

USAGE
=====

This plugin is accessed by setting `"places"` as the Command in the [REST](https://restfulapi.net) URI. There are a number of other aspects to the URI that will be explained:

    {GET|POST|PUT|DELETE} http[s]://{SERVER URL}/{json|xml|xsd}/places/[INTEGER RESOURCE IDS CSV][?{show_details|show_parents|SEARCH PARAMETERS -DISCUSSED BELOW|PUT/POST PARAMETERS -DISCUSSED BELOW}]

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
All three of these must be used together. If you specify them, then any place resources that have a long/lat that falls within the radius will be returned.
Resources without long/lat will not be returned.

- `search_longitude=`

    *Floating-Point Decimal Value.* This is the longitude of the resource, in degrees.
    
- `search_latitude=`

    *Floating-Point Decimal Value.* This is the latitude of the resource, in degrees.
    
- `search_radius=`

    *Floating-Point Decimal Value.* This is a value for a radius (not diameter) circle around the given longitude and latitude. It is in Kilometers.
    If the resource is "fuzzy," it is possible that the long/lat shown by the resource (which is deliberately inaccurate) may be outside the radius, but the actual resource location is within the radius.

- `search_address=`

    *String.* If the server is set up for geocoding, then this can be used instead of `search_longitude` and `search_latitude` (you still need `search_radius`, though). It can contain an address that will be sent to Google for geocoding.
    This is likely to only be available to logged-in users, although it is possible for the server to be configured to allow all users to use the facility.
    
**String Searches**
These searches allow you to specify a simple case-insensitive string value for the indicated resource column. You can use SQL-style wildcards (%) in the strings. Not specifying a value, but specifying the parameter name and equals sign indicates that the indicated field MUST be empty. Not specifying a field at all indicates that the value of that field is not considered in the search.
These are actual string matches. They do not do an address lookup, and resources that don't have long/lat can be returned in these searches.

- `search_name=`

    *String.* This is a simple resource name.
    
- `search_venue=`

    *String.* This is an arbitrary string value that names the venue of any address.

- `search_street_address=`

    *String.* This is an arbitrary string value that names the street address of any address.

- `search_extra_information=`

    *String.* This is an arbitrary string value that is for the "extra information" section of any address.

- `search_town=`

    *String.* This is an arbitrary string value that names the town/city/municipality of any address.

- `search_county`

    *String.* This is an arbitrary string value that names the county or sub-province of any address.

- `search_state=`

    *String.* This is an arbitrary string value that names the state or province of any address.

- `search_postal_code=`

    *String.* This is an arbitrary string value that names the postal code of any address.

- `search_nation=`

    *String.* This is an arbitrary string value that names the nation of any address.

- `search_tag8=`

    *String.* This is an arbitrary string value that can be applied to the place.

- `search_tag9=`

    *String.* This is an arbitrary string value that can be applied to the place.

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

POST AND PUT PARAMETERS
-----------------------
There are parameters that can be used to set data when creating new places (POST), or editing existing ones (PUT):

- `name=`

    *String.* This is a simple resource name.
    
- `address_venue=`

    *String.* This is an arbitrary string value that names the venue of any address.

- `address_street_address=`

    *String.* This is an arbitrary string value that names the street address of any address.

- `address_extra_information=`

    *String.* This is an arbitrary string value that is for the "extra information" section of any address.

- `address_town=`

    *String.* This is an arbitrary string value that names the town/city/municipality of any address.

- `address_county=`

    *String.* This is an arbitrary string value that names the county or sub-province of any address.

- `address_state=`

    *String.* This is an arbitrary string value that names the state or province of any address.

- `address_postal_code=`

    *String.* This is an arbitrary string value that names the postal code of any address.

- `address_nation=`

    *String.* This is an arbitrary string value that names the nation of any address.

- `tag8=`

    *String.* This is an arbitrary string value that can be applied to the place.

- `tag9=`

    *String.* This is an arbitrary string value that can be applied to the place.

- `geocode`

    *No Value Required -Just Add the Query.* If this is specified, then the BAOBAB server will make its best effort to use the address information in the record to look up a longitude and latitude. It will save this long/lat (assuming it worked) in the record.

- `reverse-geocode`

    *No Value Required -Just Add the Query.* If this is specified, then the BAOBAB server will make its best effort to use the long/lat information in the record to look up an address. It will save this address (assuming it worked) in the record.

DELETE OPERATIONS
-----------------
Delete is quite simple. Just select one or more resources (either by direct selection, or by a search), and specify DELETE as the HTTP method.

Any records within that set that are writeable by the current login will be deleted, and a detailed report will be returned in the chosen format.

LICENSE
=======

![Little Green Viper Software Development LLC](images/viper.png)
© Copyright 2018, [Little Green Viper Software Development LLC](https://littlegreenviper.com).
This code is ENTIRELY proprietary and not to be reused, unless under specific, written license from [Little Green Viper Software Development LLC](https://littlegreenviper.com).
