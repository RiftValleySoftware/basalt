**1.1.0.3000** *(March 1, 2021)*

- Added support for personal tokens.

**1.0.10.3000** *(December 15, 2020)*

- Added support for a fast lookup of logins.

**1.0.9.3000** *(December 12, 2020)*

- Fixed a bug in the new fast user query.

**1.0.8.3000** *(December 9, 2020)*

- Added support for a fast query of visible users.

**1.0.7.3000** *(November 21, 2020)*

- Updated ANDISOL (Fixes issue with converting users while logged in as God).

**1.0.6.3000** *(November 7, 2020)*

- Added an ob GZ handler to the main entrypoint, just for the heck of it.
- Added a couple more handlers, to ensure proper facade against the lower levels.

**1.0.5.3000** *(November 6, 2020)*

- Added some calls to avoid BASALT accessing the CHAMELEON instance directly. ANDISOL is supposed to provide direct access to everything beneath, in an opaque manner.

**1.0.4.3000** *(November 5, 2020)*

- Added Support for getting user IDs for token access.

**1.0.3.3000** *(October 29, 2020)*

- Added Support for counting token access.

**1.0.2.3000** *(October 27, 2020)*

- Updated ANDISOL.

**1.0.1.3000** *(September 12, 2020)*

- Fixed a possible security issue with the God Mode login (bad touch).

**1.0.0.3004** *(November 4, 2018)*

- Added the baseline/version command.

**1.0.0.3003** *(October 31, 2018)*

- Switched to MIT License.
- Also, I am binning the "two-track" branch system (master/release). It causes big pain with submodules. From now on, it will be One Branch to Rule them All, and in the Darkness Bind Them, with tags for releases.
- Happy Halloweeen! Boo! I fixed a bug, where additional parameters to login and user POST transactions were not being saved in the record.

**1.0.0.3002** *(October 29, 2018)*

- Updated ANDISOL.

**1.0.0.3001** *(September 4, 2018)*

- The HTTPS Port wasn't being sensed properly.

**1.0.0.3000** *(September 3, 2018)*

- Updated ANDISOL.
- Added a 'visibility' command to the baseline plugin. This is a way to test records and tokens.
- Fixed a bug in the description and name specification in the things plugin text search.
- Added the 'bulk-loader' baseline command.
- Added the 'backup' baseline command.
- Improved the API documentation.
- Tightened up the security and robustness of the user creation and deletion.
- Added the ability to extract the API Key and Server Secret from GET parameters, as FastCGI won't provide them in the AUTH header section.
- Addressed a warning in the people plugin.
- Made the methods for changing login id token lists more flexible and secure.
- Added a manager-only login exists test.
- Fixed a bug, where the check for a comma in the thing key always came up snake-eyes; even when there was a comma.

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
