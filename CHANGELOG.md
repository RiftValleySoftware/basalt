**1.0.0.2002** *(TBD)*

- Updated ANDISOL.
- Added a 'visibility' command to the baseline plugin. This is a way to test records and tokens.
- Fixed a bug in the description and name specification in the things plugin text search.

**1.0.0.2001** *(July 22, 2018)*

- Updated the baseline schema for people, places and things.
- Tweaked the token handler to make sure that the God login gets all available tokens.
- The people plugin needed to return a 403 if someone tried to get logins when not logged in.
- Only return the 'people' response in the people plugin if the user is not logged in.
- Tweaked the schema and response of the people plugin for a better user experience via the REST API.
- Removed unnecessary documentation. I will be relying on the README files.
- Tweaked the plugins to be a bit more robust in display of detailed descriptions.
- Specifying "login_user" in the people plugin now also triggers "show_details".
- Added the "serverinfo" response (God login only).
- Added explicit support for compressed HTTP response.
- Made the class handler code a lot faster.
- Added handling for removing ALL children.
- Made removing child objects in batch mode a lot faster.
- Added a general exception catcher (throws a 500 back at the caller).
- Corrected the places XML schema for new places.
- Made sure that distance_in_km is always a float.
- Added string and distance searches to the people plugin.
- Made corrections to the people and things XML schema documents.

**1.0.0.2000** *(July 6, 2018)*

- Tiny adjustment to the BAOBAB logo.
- Renamed the 'users' plugin to 'people'.
- Made test reporting more efficient.
- Massive work on the plugins. The plugins are now complete, but more testing and documentation needs to be done.

**1.0.0.1001** *(June 11, 2018)*

- Improving BASALT documentation.
- Added support for explicit LOGOUT.
- Added support for API key age.
- Continuing to improve the tests.
- Added support for logging REST calls.
- Completed the basic users REST plugin.
- Updated ANDISOL/COBRA/CHAMELEON/BADGER.

**1.0.0.1000** *(June 9, 2018)*

- Even though the plugins aren't complete, I'm calling this "alpha," mainly as a marker of progress. The infrastructure is solid. We just need to tweak up the details.

**1.0.0.0000** *(June 3, 2018)*

- Initial Development Tag.