##FrontPlate - Template driven CMS
Note: *This project is in heavy development*

### Technical Info
- This CMS uses a PHP PDO to a SQLite database stored at /common/database.sqlite3
- The admin panel is built using Backbone.js as well as Require.js to load in environment modules 

### Installing
1. Move all the files to the proper location in your development environment (htdocs)
2. Hit your development environments root URL.  You should hit the automatic installer.
3. At this point everything should be installed, browsing to [Your Dev Env URL]/admin/ should render the admin panel. (Default login is admin/admin)

### Contributing
Anyone and everyone welcome! Send me pull requests, or even just logging bugs for me! Any contribution to this project is much appreciated at this point.

### Major Todo Items
- Media section, upload/delete, attach to pages
- Template helpers, navigation rendering functions etc
- Admin settings, site title, add/delete/edit accounts etc
- Subpages/multi-level site structure, an reordering of that
- Support for 3rd party plugins
- Build/Style out installer
- Automatic update system for core, an eventually plugins
- Many, many more things!